<?php

use yii\db\Migration;

/**
 * Class m180609_182705_add_auto_increment_to_tbl_users
 */
class m180609_182705_add_auto_increment_to_tbl_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $createSequenceSql = "create sequence USERS_AUTO_INC_SEQ 
start with 1 
increment by 1 
nomaxvalue
nocache";

        $createTriggerSql = "create or replace trigger USERS_AUTO_INC_SEQ_TRIGGER
before insert on USERS
for each row
begin
select USERS_AUTO_INC_SEQ.nextval into :new.id_user from dual;
end;";

        $this->execute($createSequenceSql);
        $this->execute($createTriggerSql);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute("DROP SEQUENCE USERS_AUTO_INC_SEQ");
        $this->execute("DROP TRIGGER USERS_AUTO_INC_SEQ_TRIGGER");
    }
}
