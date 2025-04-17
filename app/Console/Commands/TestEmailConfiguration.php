<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmailConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Sending test email to: {$email}");
        
        try {
            Mail::raw('This is a test email to verify your Laravel mail configuration is working correctly.', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('Test Email from IT Support System');
            });
            
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send test email:');
            $this->error($e->getMessage());
            $this->newLine();
            $this->info('Check your .env configuration:');
            $this->table(
                ['Config', 'Value'],
                [
                    ['MAIL_MAILER', config('mail.default')],
                    ['MAIL_HOST', config('mail.mailers.smtp.host')],
                    ['MAIL_PORT', config('mail.mailers.smtp.port')],
                    ['MAIL_USERNAME', config('mail.mailers.smtp.username')],
                    ['MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')],
                    ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                ]
            );
        }
    }
} 