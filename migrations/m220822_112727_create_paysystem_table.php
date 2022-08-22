<?php

use app\models\Payment;
use app\models\PayMethod;
use app\models\PaysystemMethods;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%paysystem}}`.
 */
class m220822_112727_create_paysystem_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%paysystem}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->null()->defaultValue(null),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createTable('{{%pay_methods}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->null()->defaultValue(null),
        ]);

        $this->createTable('{{%paysystem_methods}}', [
            'id' => $this->primaryKey(),
            'paysystem_id' => $this->integer()->null()->defaultValue(null),
            'pay_method_id' => $this->integer()->null()->defaultValue(null),
            'country_list' => $this->json()->notNull(),
            'not_in_list' => $this->tinyInteger()->notNull()->defaultValue(0),
            'image_url' => $this->string()->notNull()->defaultValue(0),
            'pay_url' => $this->string()->notNull()->defaultValue(0),
            'commission' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'position' => $this->tinyInteger()->notNull()->defaultValue(1),
        ]);

        foreach ($this->paysystemList() as $payment) {
            $this->insert('{{%paysystem}}', [
                'name' => $payment,
                'status' => Payment::STATUS_ACTIVE
            ]);
        }

        foreach ($this->payMethodList() as $payMethod) {
            $this->insert('{{%pay_methods}}', [
                'name' => $payMethod,
            ]);
        }

        foreach ($this->getPayMethod() as $item) {
            $this->insert('{{%paysystem_methods}}', [
                'paysystem_id' => $item['paysystem_id'],
                'pay_method_id' => $item['pay_method_id'],
                'country_list' => $item['country_list'],
                'not_in_list' => $item['not_in_list'],
                'image_url' => $item['image_url'],
                'pay_url' => $item['pay_url'],
                'commission' => $item['commission'],
                'status' => $item['status'],
                'position' => PaysystemMethods::POSITION_FIRST,
            ]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%paysystem}}');
        $this->dropTable('{{%pay_methods}}');
        $this->dropTable('{{%paysystem_methods}}');
    }

    public function paysystemList(): array
    {
        return [
            'Service wallet',
            'Interkassa',
            'PayU',
            'EasyPay',
            'CardPay',
            'PayPal',
            'GooglePay',
            'ApplePay'
        ];
    }

    public function payMethodList(): array
    {
        return [
            'Банківські карти',
            'LiqPay',
            'Карти VISA / MasterCard',
            'QIWI гаманець',
            'Термінали IBOX',
            'Локальні карти Индии',
            'Яндекс гаманець',
            'Мій гаманець'
        ];
    }

    public function getPayMethod(): array
    {
        return [
            [
                'paysystem_id' => 1,
                'pay_method_id' => 8,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/servise_wallet.jpg',
                'pay_url' => '/pay/service-wallet/card',
                'commission' => 0,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 2,
                'pay_method_id' => 1,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/interkassa.jpg',
                'pay_url' => '/pay/interkassa/card',
                'commission' => 2.5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 2,
                'pay_method_id' => 2,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/interkassa.jpg',
                'pay_url' => '/pay/interkassa/liqpay',
                'commission' => 2.5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 2,
                'pay_method_id' => 5,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/interkassa.jpg',
                'pay_url' => '/pay/interkassa/ibox',
                'commission' => 3,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_SECOND
            ],
            [
                'paysystem_id' => 3,
                'pay_method_id' => 4,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/payU.jpg',
                'pay_url' => '/pay/pay-u/qiwi',
                'commission' => 3,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 3,
                'pay_method_id' => 6,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/img/payU.jpg',
                'pay_url' => '/pay/pay-u/local-card',
                'commission' => 1,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 3,
                'pay_method_id' => 7,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/payU.jpg',
                'pay_url' => '/pay/pay-u/card',
                'commission' => 6,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 5,
                'pay_method_id' => 3,
                'country_list' => ['IT', 'ES'],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/cardPay.jpg',
                'pay_url' => '/pay/pay-u/card',
                'commission' => 5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 4,
                'pay_method_id' => 3,
                'country_list' => ['IN'],
                'not_in_list' => Payment::STATUS_ACTIVE,
                'image_url' => '/img/easyPay.jpg',
                'pay_url' => '/pay/easy-pay/card',
                'commission' => 2,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 6,
                'pay_method_id' => 3,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/payPal.jpg',
                'pay_url' => '/pay/pay-pal/card',
                'commission' => 2.5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 7,
                'pay_method_id' => 3,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/payPal.jpg',
                'pay_url' => '/pay/pay-pal/card',
                'commission' => 2.5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
            [
                'paysystem_id' => 8,
                'pay_method_id' => 3,
                'country_list' => [],
                'not_in_list' => Payment::STATUS_INACTIVE,
                'image_url' => '/img/payPal.jpg',
                'pay_url' => '/pay/pay-pal/card',
                'commission' => 2.5,
                'status' => Payment::STATUS_ACTIVE,
                'position' => PaysystemMethods::POSITION_FIRST
            ],
        ];
    }
}
