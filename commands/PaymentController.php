<?php

namespace app\commands;

use app\models\PaymentTypeSelector;
use yii\console\Controller;

class PaymentController extends Controller
{
    public function actionGetPaymentMethods($productType, $amount, $lang, $countryCode, $userOs)
    {
        print "Start search payment methods \n";
        if (empty($productType) || empty($amount) || empty($lang) || empty($countryCode) || empty($userOs)) {
            print "Input data are invalid. Please check data and try again \n";
            return false;
        }

        if (!in_array($lang, ['en', 'es', 'uk'])) {
            print "Incorrect language. Please check data and try again \n";
            return false;
        }


        $paymentTypeSelector = new PaymentTypeSelector([
            'product_type' => $productType,
            'amount' => $amount,
            'language' => $lang,
            'country_code' => $countryCode,
            'user_os' => $userOs
        ]);

        var_dump($paymentTypeSelector->getButtons());
        print "Done \n";
        return true;
    }
}