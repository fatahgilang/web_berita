# News-Banner Cascade Delete Documentation

## Overview
This feature automatically deletes associated banner records when a news article is deleted from the admin panel. This ensures data integrity and prevents orphaned banner records.

## Implementation Details

### 1. Database Level (Foreign Key Constraint)
**File:** `database/migrations/2025_10_12_020536_add_foreign_key_to_banners_table.php`

- Added foreign key constraint on `banners.news_id` that references `news.id`
- Configured with `onDelete('cascade')` to automatically delete banners at database level
- Migration also cleans up any existing orphaned banner records

**Benefits:**
- Database-level integrity enforcement
- Works even if Laravel code is bypassed
- Fastest deletion mechanism

### 2. Application Level (Model Observer)
**File:** `app/Observers/NewsObserver.php`

The `NewsObserver` class handles the deletion at the application level:

```php
public function deleting(News $news): void
{
    // Delete associated banner(s) when news is being deleted
    Banner::where('news_id', $news->id)->delete();
}
```

**Benefits:**
- Allows for additional cleanup logic if needed
- Can trigger other events or notifications
- More flexible for future enhancements

### 3. Model Configuration
**File:** `app/Models/News.php`

The News model is configured to use the observer:

```php
#[ObservedBy([NewsObserver::class])]
class News extends Model
{
    // ... model code
}
```

## How It Works

### Deletion Flow:

1. **User deletes news** from Filament admin panel
2. **Laravel triggers** the `deleting` event on News model
3. **NewsObserver** intercepts the event and deletes associated banners
4. **News record** is deleted
5. **Database foreign key** ensures any remaining banner references are cleaned up

### Edit Flow:

When editing news:
- News can be edited normally without affecting banners
- Banners are only deleted when the news itself is deleted
- If you need to remove a news from banners, delete the banner record directly

## Testing

### Manual Testing in Admin Panel:

1. **Login to Filament admin** (usually at `/admin`)
2. **Navigate to News** resource
3. **Find a news** that has a banner associated
4. **Delete the news** using the delete action
5. **Check Banners** resource - the associated banner should be gone

### Automated Testing Script:

Run the test script:
```bash
php artisan tinker
```

Then in tinker:
```php
include 'tests/test_cascade_delete.php';
```

Or create a proper test:
```bash
php artisan test --filter CascadeDeleteTest
```

### Verification Query:

Check for orphaned banners:
```php
// In tinker
DB::table('banners')
    ->whereNotIn('news_id', function($query) {
        $query->select('id')->from('news');
    })
    ->count(); // Should return 0
```

## Admin Panel Integration

### Filament Resource Actions:

The delete action in `NewsResource` automatically triggers the cascade:

```php
// In NewsResource.php table actions
DeleteAction::make(),
```

### Bulk Delete:

Bulk deletion also works:

```php
// In NewsResource.php bulk actions
DeleteBulkAction::make(),
```

Both will trigger the observer and cascade delete.

## Edge Cases Handled

1. **Orphaned Banners**: Migration cleans up existing orphaned records
2. **Multiple Banners**: While the relationship is `hasOne`, the observer handles any number of banners
3. **Soft Deletes**: If you implement soft deletes later, you'll need to update the observer
4. **Transaction Rollback**: Both observer and foreign key ensure atomicity

## Future Enhancements

### Possible Additions:

1. **Soft Delete Support**:
```php
// In News model
use SoftDeletes;

// In Observer
public function forceDeleting(News $news): void
{
    $news->banner()->forceDelete();
}
```

2. **Cascade on Restore**:
```php
public function restoring(News $news): void
{
    // Restore associated banner if it was soft deleted
}
```

3. **Activity Logging**:
```php
public function deleting(News $news): void
{
    activity()
        ->performedOn($news)
        ->withProperties(['banner_deleted' => $news->banner?->id])
        ->log('News deleted with banner');
    
    Banner::where('news_id', $news->id)->delete();
}
```

4. **Notification on Delete**:
```php
public function deleted(News $news): void
{
    // Notify admins that a news with banner was deleted
    Notification::make()
        ->title('News with banner deleted')
        ->success()
        ->send();
}
```

## Troubleshooting

### Banner Not Deleting:

1. Check observer is registered:
```php
// News model should have:
#[ObservedBy([NewsObserver::class])]
```

2. Verify foreign key exists:
```bash
php artisan migrate:status
```

3. Check database constraint:
```sql
SHOW CREATE TABLE banners;
```

### Rollback Migration:

If you need to rollback:
```bash
php artisan migrate:rollback --step=1
```

This will:
- Remove the foreign key constraint
- Keep the existing data

## Security Considerations

1. **Authorization**: Ensure users have proper permissions to delete news
2. **Confirmation**: Filament automatically shows delete confirmation
3. **Audit Trail**: Consider adding activity logging for compliance
4. **Backup**: Always backup data before bulk deletions

## Performance

- **Database Level**: Fastest, handled by MySQL/PostgreSQL
- **Observer Level**: Minimal overhead, single query
- **Bulk Deletes**: Efficient with both methods

## Related Files

- `app/Models/News.php` - News model with observer
- `app/Models/Banner.php` - Banner model with relationship
- `app/Observers/NewsObserver.php` - Observer handling cascade
- `database/migrations/2025_10_12_020536_add_foreign_key_to_banners_table.php` - Migration
- `app/Filament/Resources/News/NewsResource.php` - Admin interface

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable query logging to debug
3. Use tinker to test relationships

---

**Last Updated**: 2025-10-12
**Laravel Version**: 11.x
**Filament Version**: 3.x
