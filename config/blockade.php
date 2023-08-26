<?php

return [
    /**
     * If your User model different to the default, you can specify it here.
     */
    'user_model' => 'App\Models\User',

    /**
     * Specify the user model's foreign key.
     */
    'user_foreign_key' => 'user_id',

    /**
     * Specify the table name for the blocks table.
     */
    'blocks_table' => 'blocks',

    /**
     * Specify the foreign key for the blocker.
     */
    'blocker_foreign_key' => 'blocker_id',

    /**
     * Specify the foreign key for the blocked.
     */
    'blocked_foreign_key' => 'blocked_id',

    /**
     * Schedule the cleanup of expired blocks.
     */
    'schedule_cleanup' => false,
];
