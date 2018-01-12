<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contact`.
 */
class m180112_144154_create_contact_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CONTACT', [
            'ID' => $this->primaryKey(),
            'NAME' => $this->string(15)->notNull(),
            'EMAIL' => $this->string(20)->notNull()->unique(),
            'SUBJECT' => $this->string(50)->notNull(),
            'BODY' => $this->string(300)->notNull(),
            'CREATED' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CONTACT');
    }
}
