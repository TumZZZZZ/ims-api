<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseApi;
use App\Services\SVSetting;
use Illuminate\Http\Request;

class SettingController extends BaseApi
{
    public function getService()
    {
        return new SVSetting();
    }

    public function telegramConfig()
    {
        $tab = 'telegram_config';
        return view('setting.index', [
            'receive_channel_id' => $this->getService()->getReceiveInvoiceChannelId(),
            'stock_alert_channel_id' => $this->getService()->getLowerStockAlertChannelId(),
        ])->with('activeTab', $tab);
    }

    public function setupConfig(Request $request)
    {
        try {
            $request->validate([
                'tab' => 'required|string|in:telegram_config',
                'type' => 'required|string|in:receive_invoice',
                'channel_id' => 'nullable|string',
            ]);
            $data = $this->getService()->setupConfig($request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }

    public function sendTest(Request $request)
    {
        try {
            $data = $this->getService()->sendTest($request->all());
            return $this->responseSuccess($data);
        } catch (\Throwable $th) {
            return $this->responseError($th);
        }
    }
}
