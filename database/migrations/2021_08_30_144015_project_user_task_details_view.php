<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProjectUserTaskDetailsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement($this->dropView());
    }


    private function createView(): string
    {
        return <<<SQL

            CREATE VIEW project_user_task_details_view AS
                SELECT 
                     tbl_project.id, 
                     tbl_project.project_name, 
                     tbl_project.project_description,
                     tbl_project.start_date,
                     tbl_project.end_date,
                     tbl_project.target_end_date,
                     tbl_project.end_date,
                     tbl_task.id,
                     tbl_task.task_title,
                     tbl_task.task_details,
                     tbl_task_to_project.task_id,
                     tbl_task_to_user.user_id,
                     tbl_user.username,
                     tbl_user.email,
                     FROM tbl_project LEFT JOIN tbl_task ON tbl_project.id=tbl_task.id
                     FROM tbl_task LEFT JOIN tbl_task_to_project ON tbl_task.id=tbl_task_to_project.task_id
                     FROM tbl_task_to_project LEFT JOIN tbl_task_to_user ON tbl_task_to_project.task_id=tbl_task_to_user.user_id
                     FROM tbl_task_to_user LEFT JOIN tbl_user ON tbl_task_to_user.user_id=tbl_user.id;

            SQL;
    }
    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `project_user_task_details_view`;
            SQL;
    }
}
