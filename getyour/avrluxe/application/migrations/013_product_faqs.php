<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_product_faqs extends CI_Migration
{
    public function up()
    {

        $fields = array(
            'updated_by' => array(
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE,
                'default'        => 0,
                'after'          => 'deliver_by'
            )
        );
        $this->dbforge->add_column('order_items', $fields);

        $fields = array(
            'is_local_pickup' => array(
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
                'default'        => 0,
                'after'          => 'attachments'
            ),
            'seller_notes' => array(
                'type'           => 'TEXT',
                'NULL'           => TRUE,
                'after'          => 'is_local_pickup'
            ),
            'pickup_time' => array(
                'type'           => 'DATETIME',
                'NULL'           => TRUE,
                'after'          => 'seller_notes'
            )
        );
        $this->dbforge->add_column('orders', $fields);

        $fields = array(
            'bonus_type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '30',
                'NULL'           => TRUE,
                'default'        => 'percentage_per_order',
                'after'          => 'address'
            )
        );
        $this->dbforge->add_column('users', $fields);

        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,

            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11',

            ],
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => '11',

            ],
            'votes' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'default'        => 0,
            ],
            'question' => [
                'type'           => 'TEXT',
                'NULL'           => TRUE,
                'default'        => 0,
            ],
            'answer' => [
                'type'           => 'TEXT',
                'NULL'           => TRUE,
                'default'        => 0,
            ],
            'answered_by' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'default'        => 0,
            ],
            'date_added TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('product_faqs');
    }
    public function down()
    {
        $this->dbforge->drop_column('users', 'bonus_type');
        $this->dbforge->drop_column('order_items', 'updated_by');
        $this->dbforge->drop_column('orders', 'is_local_pickup');
        $this->dbforge->drop_column('orders', 'seller_notes');
        $this->dbforge->drop_column('orders', 'pickup_time');
        $this->dbforge->drop_table('product_faqs');
    }
}
