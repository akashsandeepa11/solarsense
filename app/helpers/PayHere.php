<?php
/**
 * PayHere Payment Gateway Helper
 */
class PayHere {
    
    public static function getCheckoutUrl() {
        if (defined('PAYHERE_SANDBOX') && PAYHERE_SANDBOX) {
            return 'https://sandbox.payhere.lk/pay/checkout';
        }
        return 'https://www.payhere.lk/pay/checkout';
    }
    
    public static function generateHash($merchantId, $orderId, $amount, $currency, $merchantSecret) {
        $formattedAmount = number_format($amount, 2, '.', '');
        return strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $formattedAmount . 
                $currency . 
                strtoupper(md5($merchantSecret))
            )
        );
    }
    
    public static function verifyCallback($postData, $merchantSecret) {
        $merchantId = $postData['merchant_id'] ?? '';
        $orderId = $postData['order_id'] ?? '';
        $paymentAmount = $postData['payhere_amount'] ?? '';
        $payhereCurrency = $postData['payhere_currency'] ?? '';
        $statusCode = $postData['status_code'] ?? '';
        $md5sig = $postData['md5sig'] ?? '';
        
        $localSig = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $paymentAmount . 
                $payhereCurrency . 
                $statusCode . 
                strtoupper(md5($merchantSecret))
            )
        );
        
        return ($localSig === $md5sig);
    }
    
    public static function isPaymentSuccessful($statusCode) {
        return (int)$statusCode === 2;
    }
    
    public static function getStatusMessage($statusCode) {
        $statuses = [
            2 => 'Payment Successful',
            0 => 'Payment Pending',
            -1 => 'Payment Cancelled',
            -2 => 'Payment Failed',
            -3 => 'Payment Chargedback'
        ];
        return $statuses[(int)$statusCode] ?? 'Unknown Status';
    }
}
?>
