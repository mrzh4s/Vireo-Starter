import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet';

export function NotificationsSheet({ trigger }) {
  return (
    <Sheet>
      <SheetTrigger asChild>{trigger}</SheetTrigger>
      <SheetContent>
        <SheetHeader>
          <SheetTitle>Notifications</SheetTitle>
        </SheetHeader>
        <div className="py-4">
          <p className="text-sm text-muted-foreground">
            No notifications yet.
          </p>
        </div>
      </SheetContent>
    </Sheet>
  );
}
