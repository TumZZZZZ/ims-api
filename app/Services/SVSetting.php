<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\Meta;
use Illuminate\Support\Facades\Auth;

class SVSetting
{
    public function getReceiveInvoiceChannelId()
    {
        $user = Auth::user();
        return Meta::where('key', Constants::TELEGRAM_RECEIVE_INVOICE)
            ->where('object_id', $user->active_on)
            ->first()
            ->value ?? null;
    }

    public function getLowerStockAlertChannelId()
    {
        $user = Auth::user();
        return Meta::where('key', Constants::TELEGRAM_LOWER_STOCK_ALERT)
            ->where('object_id', $user->active_on)
            ->first()
            ->value ?? null;
    }

    public function setupConfig(array $params)
    {
        $user = Auth::user();
        $tab = $params['tab'];
        $type = $params['type'];
        $channelId = $params['channel_id'];

        switch ($tab) {
            case Constants::TELEGRAM_CONFIG_TAB:
                switch ($type) {
                    case Constants::TELEGRAM_CONFIG_TYPE_RECEIVE_INVOICE:
                        Meta::updateOrCreate([
                            'key' => Constants::TELEGRAM_RECEIVE_INVOICE,
                            'object_id' => $user->active_on,
                        ],[
                            'value' => $channelId,
                        ]);
                        break;
                    case Constants::TELEGRAM_CONFIG_TYPE_LOWER_STOCK_ALERT:
                        Meta::updateOrCreate([
                            'key' => Constants::TELEGRAM_LOWER_STOCK_ALERT,
                            'object_id' => $user->active_on,
                        ],[
                            'value' => $channelId,
                        ]);
                        break;
                }
                break;
        }

        return [
            'message' => __('configuration_saved_successfully'),
            'channel_id' => $channelId,
        ];
    }

    public function sendTest(array $params)
    {
        $channelId = $params['channel_id'];
        $user = Auth::user();
        sendMessageToTelegram([
            'chat_id' => getTelegramChannelIDFormatted($channelId),
            'text' => __('this_is_a_test_message_from', ['object_name' => $user->getActiveBranch()->name]),
            'parse_mode' => 'HTML'
        ]);
        return [
            'message' => __('sent_successfully'),
            'channel_id' => $channelId,
        ];
    }
}
