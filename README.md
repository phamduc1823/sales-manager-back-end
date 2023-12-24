### Cài đặt
```shc
composer install
```

### Migrate cho landlord database (main database)
```shc
php artisan migrate --database=landlord --seed
```

### Migrate cho tất cả các tenants có trong bảng tenants của landlord database
```shc
php artisan tenants:artisan "migrate --path=database/migrations/tenant --database=tenant"
```

### Migrate cho tất cả các tenants có trong bảng tenants của landlord database (có seed)
```shc
php artisan tenants:artisan "migrate --path=database/migrations/tenant --database=tenant --seed"
```

### Migrate cho một tenant cụ thể (truyền tenant_id vào flag --tenant)
```shc
php artisan tenants:artisan "migrate --path=database/migrations/tenant --database=tenant" --tenant=<tenant_id>
```

### Migrate cho một tenant cụ thể (có seed)
```shc
php artisan tenants:artisan "migrate --path=database/migrations/tenant --database=tenant --seed" --tenant=<tenant_id>
```
***
## Lưu ý:
### 1. Migration
* Các migrations cho database chính (landlord) sẽ đưa vào folder `database/migrations` (mặc định)

* Tất cả các migrations cho tenant sẽ đưa vào folder `database/migrations/tenant`

    ```shc
    php artisan make:migration migration_name --path=database/migrations/tenant    
    ```

### 2. Model
* Các model của tenant sẽ đặt vào trong `app/Models/Tenant`
* Các model của landlord sẽ đặt vào trong `app/Models` (Mặc định)
* Các model của tenant phải use trait `Spatie\Multitenancy\Models\Concerns\UsesTenantConnection` 

    VD:
    ```php
    use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
    
    class ModelOfTenant extends Model
    {
    
        use UsesTenantConnection;
        
        ...
    }
    ```
* Các model của landlord thì use trait `Spatie\Multitenancy\Models\Concerns\UsesTenantConnection` (hoặc không cần) 

  VD:
    ```php
    use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
    
    class ModelOfLandlord extends Model
    {
    
        use UsesLandlordConnection;
        
        ...
    }
    ```

### 3. Route
* Các route api trả về cho tenant sử dụng sẽ được viết trong `routes/tenant_api`
* Các public route api sẽ viết trong `routes/public_api`

### 4. Lưu ý khác
* Controller của tenant sẽ viết vào `app/Http/Controllers/Tenant`, của landlord thì `app/Http/Controllers` (mặc định)
* Với các Requests, Mails, Observers, Queues, Task Schedules, ... của tenant sẽ viết vào trong `Tenant`, của landlord sẽ viết vào trong `Landlord`
  * Lấy ra tenant hiện tại, sử dụng method `current()` của lớp `app/Models/Tenant`:
      ```php
    use App\Models\Tenant;
  
    ...
  
    $tenant = Tenant::current();
  
    ...
    ```
      Hoặc `app('currentTenant')`:
      ```php
      $tenant = app('currentTenant');
      ```
  
    * Kiểm tra có tồn tại current hiện tại không, dùng method checkCurrent() của lớp `app/Models/Tenant`:
        ```php
      use App\Models\Tenant;
  
      ...
  
        Tenant::checkCurrent() // returns `true` or `false`
  
      ...
      ```
      ### 5. Các lệnh artisan khác
    * Crawl dữ liệu location của các tỉnh thành, quận huyện, phường xã
        ```shc
          php artisan crawl:geo-data    
        ```
    * Chạy db seed cho tenant
      ```shc
      php artisan tenants:artisan "db:seed"
      ```
