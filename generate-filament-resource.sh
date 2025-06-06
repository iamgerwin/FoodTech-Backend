#!/bin/bash

MODELS=(
  RestaurantBranch
  MenuCategory
  MenuItem
  MenuItemVariant
  CustomerProfile
  CustomerAddress
  Order
  OrderItem
  OrderItemVariant
  Driver
  Delivery
  Review
  Coupon
  CouponUsage
  PaymentTransaction
  Notification
  ActivityLog
)

for MODEL in "${MODELS[@]}"
do
  echo "Generating Filament resource for $MODEL..."
  php artisan make:filament-resource "$MODEL" --generate --force
done

echo "All Filament resources generated!"