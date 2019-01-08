<?php

namespace local_lor\task;

class update_videos extends \core\task\scheduled_task {

  /**
    * Return the task's name as shown in admin screens.
    *
    * @return string
    */
  public function get_name() {
    return get_string('updatevideos', 'local_lor');
  }

  /**
   * Execute the task.
   */
  public function execute() {
    
  }
}
