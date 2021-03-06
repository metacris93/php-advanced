<?php


use Phinx\Migration\AbstractMigration;

class CreatingJobsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('jobs');
        $table->addColumn('title', 'string')
            ->addColumn('description', 'text')
            ->addColumn('months', 'integer')
            ->addColumn('image', 'string', ['null' => true])
            ->addColumn('uuid', 'uuid', ['null' => true])->addIndex('uuid', ['name' => 'idx_uuid'])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('deleted_at', 'datetime', ['null' => true])
            ->create();
    }
    public function down()
    {
      $this->table('jobs')->drop()->save();
    }
}
