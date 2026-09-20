<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $patientPhone,
        public int $expiresInMinutes = 5,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP RSUD Chasan Boesoirie',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->htmlBody(),
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function htmlBody(): string
    {
        $otpCode = e($this->otpCode);
        $patientPhone = e($this->patientPhone);
        $expiresInMinutes = e((string) $this->expiresInMinutes);

        return <<<HTML
        <div style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.5;">
            <h2 style="margin: 0 0 12px;">Kode OTP RSUD Chasan Boesoirie</h2>
            <p>Kode verifikasi untuk nomor <strong>{$patientPhone}</strong> adalah:</p>
            <p style="font-size: 28px; font-weight: 700; letter-spacing: 6px; margin: 18px 0;">{$otpCode}</p>
            <p>Kode ini berlaku selama {$expiresInMinutes} menit. Jangan bagikan kode ini kepada siapa pun.</p>
            <p style="font-size: 12px; color: #64748b;">Abaikan email ini jika Anda tidak meminta kode OTP.</p>
        </div>
        HTML;
    }
}
