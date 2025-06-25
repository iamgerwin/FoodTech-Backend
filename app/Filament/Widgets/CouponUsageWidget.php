<?php

namespace App\Filament\Widgets;

use App\Models\CouponUsage;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;

class CouponUsageWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Coupon Usages';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        return CouponUsage::query()
            ->latest('used_at')
            ->with(['coupon', 'customer']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('coupon.code')->label('Coupon Code')->sortable(),
            TextColumn::make('customer.name')->label('Customer')->sortable(),
            TextColumn::make('discount_amount')->label('Discount')->money('php'),
            TextColumn::make('used_at')->label('Used At')->dateTime('Y-m-d H:i'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'used_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10];
    }
}

