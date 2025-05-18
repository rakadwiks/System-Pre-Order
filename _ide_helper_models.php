<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_division
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereNameDivision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereSlug($value)
 */
	class Division extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereNamePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereSlug($value)
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code_po
 * @property string $slug
 * @property int $product_id
 * @property int $user_id
 * @property int $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $ticket_id
 * @property int $status_id
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\statusOrder $status
 * @property-read \App\Models\Ticket|null $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereCodePo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreOrder whereUserId($value)
 */
	class PreOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code_product
 * @property string $slug
 * @property string $name_product
 * @property int $supplier_id
 * @property string $price
 * @property int $stock
 * @property int $in_stock
 * @property int $out_stock
 * @property int $final_stock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreOrder> $preOrder
 * @property-read int|null $pre_order_count
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCodeProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFinalStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereNameProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOutStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Regency> $regencies
 * @property-read int|null $regencies_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Provinces extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $province_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Provinces $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Regency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Regency extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusTicket whereUpdatedAt($value)
 */
	class StatusTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_supplier
 * @property string $phone
 * @property string|null $email
 * @property string $address
 * @property string $province_id
 * @property string $regency_id
 * @property string $country
 * @property string $postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Provinces|null $province
 * @property-read \App\Models\Regency|null $regency
 * @method static \Database\Factories\SupplierFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereNameSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereRegencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereSlug($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $division_id
 * @property int $position_id
 * @property-read \App\Models\Division $division
 * @property-read \App\Models\Position $position
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereNameTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereSlug($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code_ticket
 * @property string|null $slug
 * @property int $user_id
 * @property string $description
 * @property array<array-key, mixed>|null $photos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status_ticket_id
 * @property int $status_order_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreOrder> $preOrder
 * @property-read int|null $pre_order_count
 * @property-read \App\Models\statusOrder $statusOrder
 * @property-read \App\Models\StatusTicket $statusTicket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCodeTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatusOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatusTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUserId($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property array<array-key, mixed>|null $role
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $team_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $roles_count
 * @property-read \App\Models\Team $team
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @property-read \App\Models\Team $Team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $Ticket
 * @property-read int|null $ticket_count
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|statusOrder whereUpdatedAt($value)
 */
	class statusOrder extends \Eloquent {}
}

