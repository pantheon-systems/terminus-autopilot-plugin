---
contract_version: 2
kind: tool
service: terminus-autopilot-plugin                  # a Terminus plugin (Composer package pantheon-systems/terminus-autopilot-plugin, type terminus-plugin); nothing runs on Pantheon infrastructure

trigger: merge-to-main
merged_implies_deployed: true           # every merged PR to main is tagged and released by the workflow
other_paths_to_prod:
  - "push to `main` (any merged PR, including docs and CI-only changes) runs 'Autotag and Release', which pushes the next tag and creates a GitHub release; add `[ci skip]` or similar to the commit message to skip it (README)"
  - "pushing ANY tag (any commit, any branch) is a release: Packagist lists it and `terminus self:plugin:install` / `self:plugin:update` can resolve it for users whose Terminus matches `compatible-version`. No tag ruleset exists, so nothing restricts who can push one or from where"
  - "deleting or moving a tag in GitHub changes what Packagist will serve, with no workflow run (never do it)"
  - "editing or deleting a GitHub release does NOT change what `terminus self:plugin:install` resolves (Composer uses the Packagist tag, not the release)"
  - "a manual run of 'Test & Build' does not tag; only a push to `main` runs the tag job (verified from the workflows)"

pipeline:
  system: github-actions            # CI only verifies; the release itself is the tag job
  workflow: ".github/workflows/tag-release.yml"
  jobs_in_order: [tag-release]
  typical_minutes: 1                # inferred: v0.1.7 merge commit 13:41:33Z, release published 13:42:39Z
  minutes_by_change: {}
  watch: "Actions workflow 'Autotag and Release' for the merge commit: `gh run list -R pantheon-systems/terminus-autopilot-plugin --workflow tag-release.yml --limit 3`"
  concurrency_group: null           # none declared
  cancel_in_progress: null
  approval_gate: null               # no GitHub Environment; the merge queue plus one code-owner approval are the human gate

release:
  mechanism: "merge a PR to `main` -> 'Autotag and Release' (`pantheon-systems/action-autotag@v1.3.0`, autotag default scheme: patch bump unless commit messages say otherwise) pushes the next `v<semver>` tag and creates a GitHub release with generated notes -> Packagist lists the tag"
  tag_scheme: "v-prefixed semver since v0.1.4 (v0.1.7); 0.1.0 to 0.1.3 were bare"
  never: "move or delete a published tag (Packagist and Composer lock files key on it); publish a fixed patch instead"
  procedure: "automatic on merge; README 'Releases' (moved into DEPLOYMENT.md)"
  version_source: "the git tag only; composer.json has no `version` key (checked)"

consumers:                          # how a release reaches users; each picks it up on its own schedule
  - {channel: "Packagist pantheon-systems/terminus-autopilot-plugin", pin: "the user's or CI image's resolved version", picked_up_by: "`terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin` (Composer resolves the highest stable tag whose `extra.terminus.compatible-version` fits) and `terminus self:plugin:update`. Packagist lists a new tag on its own, no workflow step (inferred: webhook or crawl; every existing tag is listed)"}
  - {channel: "docs.pantheon.io Autopilot guides", pin: "install command", picked_up_by: "autopilot introduction and FAQ tell users to `terminus self:plugin:install terminus-autopilot-plugin`"}
  - {channel: "docs.pantheon.io Terminus plugin directory", pin: "link only", picked_up_by: "hand-maintained card in pantheon-systems/documentation src/source/content/terminus/08-directory.md; no version is pinned there"}

repo_properties:
  observed_at: 2026-11-10
  trust_level: TL4
  ccb_status: in-ccb
  owner: developer-experience
  repo_type: plugin

gating_checks:
  required: ['Wiz IaC Scanner', 'Wiz Vulnerability Scanner', 'Wiz Data Scanner']   # org ruleset 'TL4 - ...'
  approvals: {required: 1, code_owner: true, last_push_approval: false, strict_up_to_date: false, merge_queue: true}   # from `gh api repos/pantheon-systems/terminus-autopilot-plugin/rules/branches/main` (repo ruleset 'Protect main' adds a merge queue (squash), non-fast-forward and review-thread resolution on top of the org TL4 ruleset)
  run_but_not_required: ["Test & Build (all jobs)"]
  conditional: []
  release_gate: "merging to main is the release: the merge queue and one approving code owner are the only gate, and the test workflow is not a required check"

environments:
  - {name: packagist, target: "https://packagist.org/packages/pantheon-systems/terminus-autopilot-plugin", gates_prod: true}
  - {name: github-release, target: "github.com/pantheon-systems/terminus-autopilot-plugin/releases/<tag>", gates_prod: false}   # informational; does not change what Terminus resolves

artifact:
  image: null                       # not a container
  file: "the git tag (Composer builds the dist archive from it); no build, no asset"
  tag_scheme: semver
  tag_mutable: false                # by convention; no tag ruleset enforces it
  retention: "tags are kept indefinitely"
  version_signal: "`terminus self:plugin:list --format=json` -> installed_version"
  tag_to_sha: "git rev-list -n1 <tag>   # Packagist's source.reference for the version must equal it (checked for v0.1.7: equal)"

runtime: []                         # the plugin runs inside the user's Terminus, on their machine or CI

deployed_when:                      # ALL must hold
  - "the tag exists on GitHub and `git rev-list -n1 <version>` is the intended commit"
  - "Packagist lists <version> with `extra.terminus.compatible-version` as intended"
  - "`terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin` into a scratch plugins dir installs <version> and `terminus self:plugin:list` shows installed_version == <version>"

health:
  soak_minutes: 0                   # no fleet to watch; health is the smoke checks now and issue reports afterwards
  baseline: "the previous release's behaviour; no request-level signal is available to this repo"
  checks:
    - name: packagist lists the tag
      kind: command
      run: "curl -s https://repo.packagist.org/p2/pantheon-systems/terminus-autopilot-plugin.json | python3 -c 'import json,sys;v=json.load(sys.stdin)[\"packages\"][\"pantheon-systems/terminus-autopilot-plugin\"][0];print(v[\"version\"],v[\"source\"][\"reference\"],v.get(\"extra\",{}).get(\"terminus\",{}).get(\"compatible-version\"))'"
      healthy: "version == <version> and the reference equals `git rev-list -n1 <version>`"   # ran 2026-11-10 for v0.1.7: v0.1.7, reference equal
    - name: installs on current Terminus in a scratch dir
      kind: command
      run: "export TERMINUS_PLUGINS_DIR=$(mktemp -d) TERMINUS_DEPENDENCIES_BASE_DIR=$(mktemp -d) HOME=$(mktemp -d); terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin && terminus self:plugin:list --format=json"   # scratch HOME so ~/.terminus is untouched; run against Terminus 4.3.3 / PHP 8.5.12 on 2026-11-10
      healthy: "exit 0 and installed_version == <version>"
    - name: user reports
      kind: delegate
      run: "new issues on github.com/pantheon-systems/terminus-autopilot-plugin/issues since the release; support reports and #ask-hosting"
      healthy: "no new issue reporting a regression introduced in <version> in the first few days"
  known_baseline_errors:
    - "`terminus self:plugin:install` prints 'Found 3 security vulnerability advisories affecting 2 packages' on every install; it comes from Terminus' own dependency set, not from this plugin"
  dashboards: []
  alerts: []

rollback:
  options:
    - when: "<version> is broken for users and a fix will take more than a few hours"
      kind: re-release-previous
      steps: |
        1. Find the last good tag (`git tag --sort=-v:refname`; the capture below).
        2. Fix forward if possible: revert the bad change on main through a normal PR, then tag the next patch (new version, higher than <version>). Composer resolves the highest compatible stable tag, so users who install or update afterwards get the fixed code.
        3. Do NOT delete or move the bad tag. Deleting it on GitHub does not reliably remove it from Packagist or from Composer caches and lock files. [inferred]
        4. If a bad tag must not be installable at all, a Packagist maintainer can remove that version on the package page. TODO(owner): who has Packagist maintainer access.
        5. Tell users already on the bad version how to pin the last good one (below).  Also: stop the auto-tag by adding `[ci skip]` to merge messages until the revert is in.
      time_to_rollback_minutes: 30   # inferred: one revert PR (review + merge queue / approval) and one tag push
    - when: otherwise
      kind: roll-forward
      steps: "fix on main, tag the next patch"
      time_to_rollback_minutes: 30   # inferred
  not_restored:
    - "plugins already installed on users' machines and CI images built in the window (docker-build-tools-ci installs plugins at image build) keep the bad version until reinstalled or the image is rebuilt"
    - "Composer lock files that resolved <version>"
  one_way_changes: []               # checked: a release changes no server-side state; a plugin that writes to ~/.terminus or a site config would be the exception, check the diff
  currently_blocked: null
  last_exercised: null
  capture_before_merge: "record the current tag and Packagist state before tagging: `git tag --sort=-v:refname | head -3` and the Packagist check above (latest on 2026-11-10: v0.1.7)"
  who_can: ["@pantheon-systems/developer-experience: write on terminus-autopilot-plugin (merge, tag); Packagist maintainers (TODO(owner): who) can remove a version"]
  verify: "`terminus self:plugin:list --format=json` on a clean scratch install shows the intended (fixed or pinned) version; Packagist's first entry is the fixed tag"
  user_pin: "`terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin:<previous>` installs a pinned version (syntax used by the push-to-pantheon action; a fresh pinned install of rsync 1.1.0 was run 2026-11-10 and installed it). Re-installing over an existing install failed in a scratch dir with 'not a valid Packagist project' (Composer's root-package warning treated as an error): TODO(verify-on-deploy): the working upgrade/downgrade path for an existing install (uninstall, delete the plugin dir, then install pinned?)"

escalation:
  slack: "#ask-hosting"             # escalation channel for everything DevX owns
  owner_team: developer-experience
  paged: false                      # inferred: no alerts exist
  issues: "https://github.com/pantheon-systems/terminus-autopilot-plugin/issues"

preflight:
  - "gh auth status (push access to pantheon-systems/terminus-autopilot-plugin for the tag)"
  - "terminus and php on PATH (for the scratch install check)"

automation_blockers:
  - blocker: "every merge to `main` releases automatically while the test workflow is red and is not a required check: an unverified change can reach users with no human decision in between"
    clears_when: "Test & Build is green on `main` and is a required check, or the tag job depends on it"
  - blocker: "the tag job does not depend on test results (it is a separate workflow)"
    clears_when: "the tag job `needs` a passing test job, or the release is cut from a verified commit"
  - blocker: "no pre-release or staging channel: a tag is immediately what `self:plugin:install` resolves for every user, and `self:plugin:install` cannot be pointed at a candidate without a tag (a pinned `pkg:<version>` or a git URL can)"
    clears_when: "a `-rc` tag convention is adopted and documented (Composer ignores pre-release tags unless asked)"

verified: null                      # no real release has been observed for this contract yet (pass 2)
---

# Deployment

`terminus-autopilot-plugin` is a Terminus plugin. "Deploying" it means publishing a version tag that Packagist serves, so that `terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin` and `terminus self:plugin:update` resolve it. Nothing runs on Pantheon infrastructure; there is nothing to roll out or watch in a cluster. `main` is the only line (`compatible-version: ^3 || ^4`).

## How a change reaches production

1. **Open a PR to `main`.** `terminus-autopilot-plugin` has a merge queue (squash) and needs one approving code owner (`@pantheon-systems/developer-experience`). Only the Wiz scanners are required checks; 'Test & Build' runs but does not block.
2. **Merging is releasing.** Every push to `main` runs `.github/workflows/tag-release.yml`, which uses `pantheon-systems/action-autotag` to push the next version tag (`v0.1.x`, patch bump by default) and create a GitHub release with generated notes. To skip releasing, put `[ci skip]` (or another GitHub skip keyword) in the commit message (the README's old "Releases" section says the same). v0.1.7 shipped from a CI-only PR. [Observed: v0.1.7 tag commit 2026-11-09 13:41:33Z, release 13:42:39Z.]
3. **Packagist** lists the new tag on its own. Users get it the next time they run `terminus self:plugin:install` or `self:plugin:update`; CI images pick it up at their next build.

Other paths to production: any tag push is a release, whatever the commit. Deleting or moving a tag changes what Packagist serves. Editing a GitHub release changes nothing for Composer.

CI in this repo: `.github/workflows/tag-release.yml` ('Autotag and Release', every push to `main`) and `build.yml` ('Test & Build': lint, PHP compatibility, functional matrix on Terminus 3.6.2 and 4.0.1, runs on PRs, `main` pushes, nightly 06:00 UTC and manual dispatch). Recent results: Autotag and Release: 2 of 2 recent runs green. Test & Build: **the 3 most recent runs (PRs, 2026-11-10) all failed**: `composer install` cannot resolve a dependency set because Composer's security-advisory filter blocks the pinned `psy/psysh` and `justinrainbow/json-schema` versions that `pantheon-systems/terminus ^3.1` requires (there is no composer.lock).

## Live problems seen while drafting (2026-11-10)

- Test & Build is red on the 3 latest PR runs (Composer cannot resolve `pantheon-systems/terminus ^3.1` dev dependency under the advisory filter), so nothing verifies a merge that auto-releases

## How to confirm it deployed

All of these must hold (the checks were run for v0.1.7 on 2026-11-10 and returned the expected data):

```bash
# The tag points at the intended commit
git ls-remote https://github.com/pantheon-systems/terminus-autopilot-plugin.git 'refs/tags/<version>*'

# Packagist lists it, with the same commit and the intended Terminus compatibility
curl -s https://repo.packagist.org/p2/pantheon-systems/terminus-autopilot-plugin.json \
  | python3 -c 'import json,sys;v=json.load(sys.stdin)["packages"]["pantheon-systems/terminus-autopilot-plugin"][0];print(v["version"],v["source"]["reference"],v.get("extra",{}).get("terminus",{}).get("compatible-version"))'

# It installs on a current Terminus, in a scratch location (does not touch ~/.terminus)
export TERMINUS_PLUGINS_DIR=$(mktemp -d) TERMINUS_DEPENDENCIES_BASE_DIR=$(mktemp -d) HOME=$(mktemp -d)
terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin
terminus self:plugin:list --format=json      # installed_version == <version>
```

## How to confirm it's healthy

There is no telemetry, dashboard or alert for this plugin. Health means:

- **At release time:** the install check above on the Terminus versions the plugin claims (`compatible-version`). It was run on Terminus 4.3.3 / PHP 8.5.12. Run the plugin's main command against a test site only if credentials are at hand and the command is read-only.
- **In the following days:** new GitHub issues, support and Slack reports about the new version.

## Rollback

Plugins are normally fixed forward with a new patch tag. Pull a release back only when it is broken for users and a fix will take more than a few hours.

**Before tagging**, record the rollback target: the current latest tag (`git tag --sort=-v:refname | head -3`; latest on 2026-11-10: `v0.1.7`).

**To stop a bad release spreading:**

1. Revert the bad change on `main` through a normal PR and tag the next patch version (never reuse the bad version number). Composer picks the highest stable compatible tag, so new installs and updates get the fix.
2. Do not delete or move the bad tag. A Packagist maintainer can remove a version from the package page if it must not be installable at all (TODO(owner): who has that access).
3. Tell affected users how to go back: `terminus self:plugin:install pantheon-systems/terminus-autopilot-plugin:<previous>`. [inferred for an existing install: re-installing over an installed plugin failed in a scratch dir, so uninstall first, `terminus self:plugin:uninstall pantheon-systems/terminus-autopilot-plugin`; TODO(verify-on-deploy)]
4. Until the revert lands, add `[ci skip]` to merge messages so merges do not auto-release.

**Verify:** a clean scratch install (the commands above) reports the fixed or pinned version.

**What this doesn't restore:** plugins already installed on users' machines, CI images built in the meantime (`docker-build-tools-ci` installs plugins at image build), and Composer lock files that resolved the bad version. A release changes no server-side state.

Rollback has never been exercised.

## Escalation

- Owner: `developer-experience` (code owners `@pantheon-systems/developer-experience`).
- Nobody is paged and there are no alerts.
- Issues: https://github.com/pantheon-systems/terminus-autopilot-plugin/issues
- Slack: `#ask-hosting`, the escalation channel for everything DevX owns.

## Suggested pipeline changes

- Make the tag job depend on a green test run, or make the tests a required check, so an auto-release cannot ship an unverified commit.
- Add a tag ruleset so only the merged tip of `main` can be tagged and tags cannot be deleted or moved.
- Document the working downgrade path for an installed plugin (see `user_pin`).
