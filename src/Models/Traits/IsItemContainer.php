<?php

declare(strict_types=1);

namespace Tipoff\Checkout\Models\Traits;

use Carbon\Carbon;
use Tipoff\Addresses\Models\Address;
use Tipoff\Addresses\Models\DomesticAddress;
use Tipoff\Addresses\Traits\HasAddresses;
use Tipoff\Checkout\Enums\AddressTypes;
use Tipoff\Checkout\Objects\ContainerPricingDetail;
use Tipoff\Support\Contracts\Checkout\BaseItemInterface;
use Tipoff\Support\Contracts\Sellable\Fee;
use Tipoff\Support\Objects\DiscountableValue;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasUpdater;

/**
 * @property int|null id
 * @property DiscountableValue shipping
 * @property int discounts
 * @property int credits
 * @property DiscountableValue $item_amount_total
 * @property int tax
 * @property Carbon created_at
 * @property Carbon updated_at
 * // Relations
 * @property mixed location
 * // Raw Relation ID
 * @property int|null location_id
 * @property int|null creator_id
 * @property int|null updater_id
 */
trait IsItemContainer
{
    use HasCreator;
    use HasUpdater;
    use HasAddresses;

    protected static function bootIsItemContainer()
    {
        static::saving(function ($model) {
            $model->updateCalculatedValues();
        });
    }

    //region RELATIONSHIPS

    public function location()
    {
        return $this->belongsTo(app('location'));
    }

    //endregion

    //region HELPERS

    public function getShippingAddress(): ?Address
    {
        return $this->getAddressByType(AddressTypes::SHIPPING);
    }

    public function setShippingAddress(DomesticAddress $domesticAddress): Address
    {
        return $this->setAddressByType(AddressTypes::SHIPPING, $domesticAddress);
    }

    public function getBillingAddress(): ?Address
    {
        return $this->getAddressByType(AddressTypes::BILLING);
    }

    public function setBillingAddress(DomesticAddress $domesticAddress): Address
    {
        return $this->setAddressByType(AddressTypes::BILLING, $domesticAddress);
    }

    public function getFeeTotal(): DiscountableValue
    {
        return $this->getItems()
            ->filter(function (BaseItemInterface $item) {
                return $item->getSellable() instanceof Fee;
            })
            ->reduce(function (DiscountableValue $feeTotal, BaseItemInterface $item) {
                return $feeTotal->add($item->getAmountTotal());
            }, new DiscountableValue(0));
    }

    public function getBalanceDue(): int
    {
        return $this->getPricingDetail()->getBalanceDue();
    }

    public function getPricingDetail(): ContainerPricingDetail
    {
        return new ContainerPricingDetail($this);
    }

    protected function updateItemAmountTotal(): self
    {
        $this->item_amount_total = $this->getItems()->reduce(function (DiscountableValue $itemAmountTotal, BaseItemInterface $item) {
            return $itemAmountTotal->add($item->getAmountTotal());
        }, new DiscountableValue(0));

        return $this;
    }

    protected function updateTax(): self
    {
        $this->tax = $this->getItems()->sum->tax;

        return $this;
    }

    protected function updateCalculatedValues(): self
    {
        return $this->updateItemAmountTotal()->updateTax();
    }

    //endregion

    //region INTERFACE IMPLEMENTATION

    public function getItemAmountTotal(): DiscountableValue
    {
        return $this->item_amount_total ?? new DiscountableValue(0);
    }

    public function getTax(): int
    {
        return $this->tax ?? 0;
    }

    public function getShipping(): DiscountableValue
    {
        return $this->shipping ?? new DiscountableValue(0);
    }

    public function getDiscounts(): int
    {
        return $this->discounts ?? 0;
    }

    public function getCredits(): int
    {
        return $this->credits ?? 0;
    }

    public function getLocationId(): ?int
    {
        return $this->location_id;
    }

    //endregion
}
