<?php

namespace app\models;

class PaymentTypeSelector
{
    protected $productType;
    protected $amount;
    protected $language;
    protected $countryCode;
    protected $userOs;

    public function __construct($config = [])
    {
        $this->productType = $config['product_type'];
        $this->amount = $config['amount'];
        $this->language = $config['language'];
        $this->countryCode = $config['country_code'];
        $this->userOs = $config['user_os'];
    }

    public function getButtons(): array
    {
        $result = [];
        $payMethodList = PaysystemMethods::find()
            ->select(['pm.*', 'p.status as payment_status', 'p.name as payment_name', 'pms.name as method_name'])
            ->from("paysystem_methods as pm")
            ->leftJoin('paysystem as p', 'pm.paysystem_id = p.id')
            ->leftJoin('pay_methods as pms', 'pm.pay_method_id = pms.id')
            ->where(['pm.status' => Payment::STATUS_ACTIVE, 'p.status' => Payment::STATUS_ACTIVE])
            ->asArray()
            ->orderBy(['pm.position'=>SORT_ASC])
            ->all();

        if (empty($payMethodList))
            return $result;

        foreach ($payMethodList as $data) {
            $checkCountry = $this->checkCountry(json_decode($data['country_list']), empty($data['not_in_list']) ? false : true);
            if($checkCountry === false)
                continue;

            if($this->checkPayType($data['payment_name']) === false)
                continue;

            if($this->checkAmount($data['payment_name']) === false)
                continue;

            if($this->checkProductType($data['payment_name']) === false)
                continue;

            $result[] = [
                'name' => $data['method_name'],
                'paysystem' => $data['payment_name'],
                'comission' => $data['commission'],
                'image_url' => $data['image_url'],
                'pay_url' => $data['pay_url'],
            ];
            if($this->countryCode === 'UA') {
                $result[] = [
                    'name' => 'Оплата картою Приват Банку',
                    'paysystem' => $data['payment_name'],
                    'comission' => $data['commission'],
                    'image_url' => $data['image_url'],
                    'pay_url' => $data['pay_url'],
                ];
            }
        }
        return $result;
    }

    public function checkCountry(array $countryList, bool $exclude):bool
    {
        $result = true;
        $countryListCheck = empty($countryList) ? false : true;

        if ($countryListCheck && !in_array($this->countryCode, $countryList)) {
            $result = false;
        }

        if ($countryListCheck && $exclude) {
            $result = !$result;
        }

        return $result;
    }

    public function checkPayType(string $payName): bool
    {
        $result = true;
        if(strtolower($this->userOs) === 'android' && $payName !== 'GooglePay') {
            $result = false;
        }

        if(strtolower($this->userOs) === 'ios' && $payName !== 'ApplePay') {
            $result = false;
        }

        return $result;
    }

    public function checkAmount(string $name):bool
    {
        if($this->amount <= 0)
            return false;
        elseif($this->amount <= 0.3 && $name === "PayPal")
            return false;

        return true;
    }

    public function checkProductType(string $name):bool
    {
        if($this->productType === 'reward' && $this->amount <= 0.3 && $name !== 'Service wallet')
            return false;

        if($this->productType === 'walletRefill' && $name !== 'Service wallet')
            return false;

        return true;
    }
}
