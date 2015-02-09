<?php
/** ./yii migrate --migrationPath="@app/vendor/costa-rico/yii2-stars" */
use yii\db\Schema;
use yii\db\Migration;

class m150206_123253_RatingTable extends Migration
{
    public function up()
    {
        $this->createTable('rico_rating', [
            'id' => 'pk',
            'itemId' => 'int(11)',
            'userId' => 'int(11)',
            'value' => 'int(11)',
            'itemClass' => 'varchar(300)',
            'ip' => 'varchar(300)',
            'created' => 'timestamp',
            'changed' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m150206_123253_RatingTable cannot be reverted.\n";
        $this->dropTable('rico_rating');
        return false;
    }
}
