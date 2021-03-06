<?php

declare(strict_types=1);

namespace Tipoff\Checkout\View\Components\Order;

use Illuminate\View\Component;
use Illuminate\View\View;
use Tipoff\Support\Contracts\Checkout\CodedCartAdjustment;

class OrderDeductionsComponent extends Component
{
    public array $deductions;

    /**
     * @param array|CodedCartAdjustment[] $deductions
     */
    public function __construct(array $deductions)
    {
        $this->deductions = $deductions;
    }

    public function render()
    {
        if ($this->deductions) {
            /** @var View $view */
            $view = view('checkout::components.order.order-deductions');

            return $view;
        }

        return '';
    }
}
