#!/usr/bin/env bats

@test "run autopilot status command" {
  run terminus autopilot:status
  [[ $output == *"The status of Autopilot is green."* ]]
  [ "$status" -eq 0 ]
}
