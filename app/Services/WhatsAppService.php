<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function send(string $to, string $message): void
    {
        $sid   = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from  = config('services.twilio.whatsapp_from');

        if (!$sid || !$token || !$from) {
            Log::warning('WhatsApp: credenciales de Twilio no configuradas. Mensaje no enviado.', ['to' => $to]);
            return;
        }

        $to = $this->normalizePhone($to);

        Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => "whatsapp:{$from}",
                'To'   => "whatsapp:{$to}",
                'Body' => $message,
            ]);
    }

    /**
     * Asegura que el número tenga el prefijo internacional (+).
     * Si el número ya lo tiene, lo deja igual.
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\s+/', '', $phone);

        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
