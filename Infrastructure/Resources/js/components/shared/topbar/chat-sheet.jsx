import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet';

export function ChatSheet({ trigger }) {
  return (
    <Sheet>
      <SheetTrigger asChild>{trigger}</SheetTrigger>
      <SheetContent>
        <SheetHeader>
          <SheetTitle>Chat</SheetTitle>
        </SheetHeader>
        <div className="py-4">
          <p className="text-sm text-muted-foreground">
            No messages yet.
          </p>
        </div>
      </SheetContent>
    </Sheet>
  );
}
