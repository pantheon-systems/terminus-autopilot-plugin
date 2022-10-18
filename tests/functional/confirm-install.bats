#!/usr/bin/env bats

#
# confirm-install.bats
#
# Ensure that Terminus and the Composer plugin have been installed correctly
#

@test "confirm terminus version" {
  terminus --version
}

@test "Confirm frequency command is enabled." {
  run terminus help site:autopilot:frequency
  [[ $output == *"Set Autopilot run frequency for a specific site."* ]]
  [ "$status" -eq 0 ]
}
