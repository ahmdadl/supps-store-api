<?php

namespace Modules\Orders\Enums;

enum OrderStatus: string
{
    use \Modules\Core\Traits\HasEnumHelpers;

    case PENDING = "pending";
    case PROCESSING = "processing";
    case SHIPPED = "shipped";
    case DELIVERED = "delivered";
    case CANCELLED = "cancelled";
}
