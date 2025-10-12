# News-Banner Cascade Delete Flow

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    FILAMENT ADMIN PANEL                         │
│                                                                 │
│  ┌──────────────┐         ┌──────────────┐                    │
│  │  News List   │         │ Banner List  │                    │
│  │              │         │              │                    │
│  │ [Delete] ←───┼─────────┼──→ Auto Del  │                    │
│  └──────────────┘         └──────────────┘                    │
└─────────────────────────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                            │
│                                                                 │
│  ┌──────────────────────────────────────────────────┐          │
│  │         NewsResource (Filament)                  │          │
│  │  - DeleteAction::make()                          │          │
│  │  - DeleteBulkAction::make()                      │          │
│  └────────────────────┬─────────────────────────────┘          │
│                       │                                         │
│                       ▼                                         │
│  ┌──────────────────────────────────────────────────┐          │
│  │         News Model                               │          │
│  │  #[ObservedBy([NewsObserver::class])]            │          │
│  │  - deleting event triggered                      │          │
│  └────────────────────┬─────────────────────────────┘          │
│                       │                                         │
│                       ▼                                         │
│  ┌──────────────────────────────────────────────────┐          │
│  │         NewsObserver                             │          │
│  │  deleting(News $news) {                          │          │
│  │    Banner::where('news_id', $news->id)->delete() │          │
│  │  }                                               │          │
│  └────────────────────┬─────────────────────────────┘          │
└───────────────────────┼─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                               │
│                                                                 │
│  ┌──────────────────┐         ┌──────────────────┐            │
│  │   news table     │         │  banners table   │            │
│  │                  │         │                  │            │
│  │  id (PK)         │←────────│  news_id (FK)    │            │
│  │  title           │         │  ON DELETE       │            │
│  │  content         │         │  CASCADE         │            │
│  │  ...             │         │  ...             │            │
│  └──────────────────┘         └──────────────────┘            │
│                                                                 │
│  When news deleted → Foreign key constraint automatically      │
│                      deletes matching banner records            │
└─────────────────────────────────────────────────────────────────┘
```

## Deletion Sequence

```
1. Admin clicks "Delete" on a News
          ↓
2. Filament triggers DeleteAction
          ↓
3. Laravel fires 'deleting' event on News model
          ↓
4. NewsObserver intercepts the event
          ↓
5. Observer deletes associated Banner(s)
          ↓
6. News record is deleted
          ↓
7. Database foreign key ensures cleanup
          ↓
8. Success! Both News and Banner are removed
```

## Double Protection Mechanism

```
┌─────────────────────────────────────────┐
│   1. Application Level (Observer)       │
│   - Flexible, extensible                │
│   - Can add logging, notifications      │
│   - Works in all Laravel contexts       │
└─────────────────────────────────────────┘
                   +
┌─────────────────────────────────────────┐
│   2. Database Level (Foreign Key)       │
│   - Enforced by MySQL/PostgreSQL        │
│   - Cannot be bypassed                  │
│   - Fastest execution                   │
└─────────────────────────────────────────┘
                   =
┌─────────────────────────────────────────┐
│   Robust Cascade Delete System          │
│   - Data integrity guaranteed           │
│   - No orphaned records                 │
│   - Production-ready                    │
└─────────────────────────────────────────┘
```

## Example Scenarios

### Scenario 1: Single Delete
```
Before:
news table:        id=1, title="Breaking News"
banners table:     id=1, news_id=1

Action: Delete news id=1

After:
news table:        (empty - record deleted)
banners table:     (empty - auto deleted)
```

### Scenario 2: Bulk Delete
```
Before:
news table:        id=1, id=2, id=3
banners table:     id=1 (news_id=1), id=2 (news_id=2)

Action: Bulk delete news id=1,2,3

After:
news table:        (empty - all deleted)
banners table:     (empty - auto deleted)
```

### Scenario 3: News Without Banner
```
Before:
news table:        id=4, title="Regular News"
banners table:     (no banner for news_id=4)

Action: Delete news id=4

After:
news table:        (empty - record deleted)
banners table:     (unchanged - no banner to delete)
```

## Benefits Summary

✅ **Automatic**: No manual intervention needed
✅ **Safe**: Double protection (Observer + Foreign Key)
✅ **Fast**: Efficient deletion mechanism
✅ **Clean**: No orphaned data
✅ **Extensible**: Easy to add logging/notifications
✅ **Production-Ready**: Battle-tested pattern
