<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    private $apiToken;
    private $senderId;
    private $apiUrl;

    public function __construct()
    {
        $this->apiToken = config('services.textlk.api_token');
        $this->senderId = config('services.textlk.sender_id');
        $this->apiUrl = 'https://app.text.lk/api/http/sms/send';
    }

    public function sendPaymentConfirmation($customer_phone, $full_name, $amount, $loanNumber, $remainingBalance)
    {
        try {
            // Get company details from database
            $company = Company::first();
            $companyName = $company->name ?? 'Rural Development Investment';
            $hotlineNumber = $company->phone ?? '0771234567';

            $message = "Dear {$full_name},\n\n" .
                       "Your payment of LKR {$amount} for loan #{$loanNumber} has been received successfully.\n\n" .
                       "Remaining Balance: LKR {$remainingBalance}\n\n" .
                       "Thank you for your payment!\n\n" .
                       "{$companyName}\n" .
                       "Hotline: {$hotlineNumber}";

            $formattedNumber = $this->formatPhoneNumber($customer_phone);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'api_token' => $this->apiToken,
                'recipient' => $formattedNumber,
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => $message
            ]);

            $responseBody = $response->json();

            if ($response->successful() && ($responseBody['status'] ?? '') === 'success') {
                Log::info('Payment confirmation SMS sent successfully', [
                    'phone' => $formattedNumber,
                    'loan_number' => $loanNumber,
                    'amount' => $amount,
                    'company' => $companyName,
                    'hotline' => $hotlineNumber,
                    'response' => $responseBody
                ]);
                return true;
            }

            Log::error('Payment confirmation SMS sending failed', [
                'phone' => $formattedNumber,
                'loan_number' => $loanNumber,
                'response' => $responseBody
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Payment confirmation SMS sending error', [
                'message' => $e->getMessage(),
                'phone' => $customer_phone
            ]);
            return false;
        }
    }

    public function sendNewUserPassword($phone, $name, $email, $password)
    {
        try {
            // Get company details from database
            $company = Company::first();
            $companyName = $company->name ?? 'Rural Development Investment';
            $systemUrl = $company->website ?? 'your-system-url.com';
            $hotlineNumber = $company->phone ?? '0771234567';

            // Create well-formatted message with system URL above username
            $message = "Dear {$name},\n\n" .
                       "Welcome to {$companyName}!\n\n" .
                       "Your system account is ready:\n\n" .
                       "System: {$systemUrl}\n\n" .
                       "Login Details:\n" .
                       "Username: {$email}\n" .
                       "Password: {$password}\n\n" .
                       "⚠️ Please change your password after first login.\n\n" .
                       "For support, contact us:\n" .
                       "Hotline: {$hotlineNumber}\n\n" .
                       "Thank you!\n" .
                       "{$companyName}";

            $formattedNumber = $this->formatPhoneNumber($phone);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'api_token' => $this->apiToken,
                'recipient' => $formattedNumber,
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => $message
            ]);

            $responseBody = $response->json();

            if ($response->successful() && ($responseBody['status'] ?? '') === 'success') {
                Log::info('Password SMS sent successfully', [
                    'phone' => $formattedNumber,
                    'user_name' => $name,
                    'email' => $email,
                    'company' => $companyName,
                    'system_url' => $systemUrl,
                    'hotline' => $hotlineNumber,
                    'response' => $responseBody
                ]);
                return true;
            }

            Log::error('Password SMS sending failed', [
                'phone' => $formattedNumber,
                'user_name' => $name,
                'email' => $email,
                'response' => $responseBody
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Password SMS sending error', [
                'message' => $e->getMessage(),
                'phone' => $phone,
                'user_name' => $name,
                'email' => $email
            ]);
            return false;
        }
    }

    /**
     * Send loan approval notification
     */
    public function sendLoanApproval($customer_phone, $customer_name, $loanNumber, $amount, $approvalDate)
    {
        try {
            $company = Company::first();
            $companyName = $company->name ?? 'Rural Development Investment';
            $hotlineNumber = $company->phone ?? '0771234567';

            $message = "Dear {$customer_name},\n\n" .
                       "Congratulations! Your loan application has been approved.\n\n" .
                       "Loan Details:\n" .
                       "Loan #: {$loanNumber}\n" .
                       "Amount: LKR {$amount}\n" .
                       "Approved: {$approvalDate}\n\n" .
                       "Please visit our office to complete the process.\n\n" .
                       "{$companyName}\n" .
                       "Hotline: {$hotlineNumber}";

            $formattedNumber = $this->formatPhoneNumber($customer_phone);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'api_token' => $this->apiToken,
                'recipient' => $formattedNumber,
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => $message
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Loan approval SMS error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder($customer_phone, $customer_name, $loanNumber, $dueAmount, $dueDate)
    {
        try {
            $company = Company::first();
            $companyName = $company->name ?? 'Rural Development Investment';
            $hotlineNumber = $company->phone ?? '0771234567';

            $message = "Dear {$customer_name},\n\n" .
                       "Payment Reminder:\n\n" .
                       "Loan #: {$loanNumber}\n" .
                       "Due Amount: LKR {$dueAmount}\n" .
                       "Due Date: {$dueDate}\n\n" .
                       "Please make your payment on time to avoid late fees.\n\n" .
                       "For inquiries:\n" .
                       "{$companyName}\n" .
                       "Hotline: {$hotlineNumber}";

            $formattedNumber = $this->formatPhoneNumber($customer_phone);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'api_token' => $this->apiToken,
                'recipient' => $formattedNumber,
                'sender_id' => $this->senderId,
                'type' => 'plain',
                'message' => $message
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Payment reminder SMS error: ' . $e->getMessage());
            return false;
        }
    }

    private function formatPhoneNumber($customer_phone)
    {
        // Clean and format for Sri Lankan numbers
        $cleaned = preg_replace('/[^0-9]/', '', $customer_phone);

        // Convert 07... numbers to 947...
        if (strlen($cleaned) === 10 && str_starts_with($cleaned, '0')) {
            return '94' . substr($cleaned, 1);
        }

        return $cleaned;
    }
}
