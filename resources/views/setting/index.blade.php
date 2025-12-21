@extends('layouts.app')

@section('title', __('setting'))
@section('header-title', __('setting'))

@push('styles')
    <style>
        /* Container */
        .cycle-tab-container {
            margin: 0 auto;
            font-size: 16px;
        }

        /* Tabs wrapper */
        .cycle-tabs {
            display: flex;
            gap: 20px;
            padding: 0;
            margin: 0;
            list-style: none;
            border-bottom: 1px solid #ddd;
        }

        /* Tab item */
        .cycle-tab-item {
            width: 180px;
            text-align: center;
            position: relative;
        }

        /* Tab link */
        .cycle-tab-item a {
            display: block;
            padding: 12px 0;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.2s ease;
            text-align: center;
        }

        /* Hover / focus */
        .cycle-tab-item a:hover {
            color: var(--gold);
        }

        /* Active underline animation */
        .cycle-tab-item::after {
            content: "";
            display: block;
            height: 3px;
            background-color: var(--gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }

        /* Active tab */
        .cycle-tab-item.active a {
            color: var(--gold);
        }

        .cycle-tab-item.active::after {
            transform: scaleX(1);
        }

        /* Fade animation (if needed for content) */
        .fade {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .fade.active {
            opacity: 1;
        }

    </style>
@endpush

@php
    $telegramConfigTab = 'telegram_config';
    $channels = [
        ['label' => 'receive_invoice_channel', 'value' => $receive_channel_id, 'type' => 'receive_invoice'],
        ['label' => 'lower_stock_alert_channel', 'value' => $stock_alert_channel_id, 'type' => 'lower_stock_alert'],
    ];
@endphp

@section('content')

    <div class="cycle-tab-container">
        <ul class="cycle-tabs">
            <li class="cycle-tab-item {{ $activeTab == $telegramConfigTab ? 'active' : '' }}">
                <a href="{{ route('setting.telegram-config') }}">@lang('telegram_config')</a>
            </li>
        </ul>
    </div>

    <div style="padding-top:35px;"></div>

    @if ($activeTab == $telegramConfigTab)
        @foreach ($channels as $index => $channel)
            <div style="display:flex; align-items:flex-end; gap:12px; margin-bottom:15px;">

                <!-- Auto Save Form -->
                <form class="autoSaveForm" data-index="{{ $index }}" action="{{ route('setting.setup-config') }}" method="POST">
                    @csrf
                    <label>{{ __('' . $channel['label']) }}</label>
                    <input type="hidden" name="tab" value="telegram_config">
                    <input type="hidden" name="type" value="{{ $channel['type'] }}">
                    <div style="width: 450px;">
                        <input type="text" class="channelInput" name="channel_id"
                            value="{{ old('channel_id', $channel['value']) }}" placeholder="{{ __('enter_channel_id') }}">
                    </div>
                </form>

                <!-- Send Test Form -->
                <form class="sendTestForm" data-index="{{ $index }}" action="{{ route('setting.send-test') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="channel_id" class="sendChannelId" value="{{ $channel['value'] }}">
                    <button type="submit" class="submit-btn" style="height:50px;">
                        @lang('send_test')
                    </button>
                </form>

            </div>
        @endforeach
    @endif

    @include('modal')

    @push('scripts')
        <script>
            // Tab-Pane change function
            const tabs = document.querySelectorAll('.cycle-tab-item');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                });
            });

            document.addEventListener('DOMContentLoaded', function() {

                const toast = document.getElementById('toast');

                function showToast(message, success = true) {
                    toast.textContent = message;
                    toast.style.color = success ? '#28a745' : '#dc3545';
                    toast.style.border = success ? '1px solid #28a745' : '1px solid #dc3545';
                    toast.style.display = 'block';
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 5000);
                }

                // Handle all auto-save forms
                document.querySelectorAll('.autoSaveForm').forEach(form => {
                    const input = form.querySelector('.channelInput');
                    const index = form.dataset.index;
                    let debounceTimer = null;
                    let lastValue = input.value;

                    input.addEventListener('input', function() {
                        const value = input.value.trim();
                        if (value === lastValue) return;
                        clearTimeout(debounceTimer);

                        debounceTimer = setTimeout(() => {
                            lastValue = value;
                            const formData = new FormData(form);

                            fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': form.querySelector('[name=_token]')
                                            .value,
                                        'Accept': 'application/json'
                                    },
                                    body: formData
                                })
                                .then(res => res.ok ? res.json() : Promise.reject())
                                .then(data => {
                                    // Update corresponding send test hidden input
                                    const sendForm = document.querySelector(
                                        `.sendTestForm[data-index="${index}"]`);
                                    const sendInput = sendForm.querySelector(
                                        '.sendChannelId');
                                    if (sendInput) sendInput.value = data.data.channel_id ??
                                        sendInput.value;
                                    showToast(data.data.message ?? 'Saved');
                                })
                                .catch(() => showToast('Auto save failed', false));

                        }, 500);
                    });
                });

                // Handle all send-test forms
                document.querySelectorAll('.sendTestForm').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(res => res.ok ? res.json() : Promise.reject())
                            .then(data => showToast(data.data.message ?? 'Test message sent'))
                            .catch(() => showToast('Failed to send test message', false));
                    });
                });

            });
        </script>
    @endpush

@endsection
