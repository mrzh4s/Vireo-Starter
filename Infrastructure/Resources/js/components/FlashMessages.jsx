import { useEffect, useRef } from 'react';
import { usePage } from '@inertiajs/react';
import { toast } from 'sonner';

export function FlashMessages() {
  const { flash } = usePage().props;
  const shownMessages = useRef(new Set());

  useEffect(() => {
    // Handle success messages
    if (flash?.success && !shownMessages.current.has(`success:${flash.success}`)) {
      toast.success(flash.success, {
        id: 'flash-success',
        duration: 4000,
      });
      shownMessages.current.add(`success:${flash.success}`);
    }

    // Handle error messages
    if (flash?.error && !shownMessages.current.has(`error:${flash.error}`)) {
      toast.error(flash.error, {
        id: 'flash-error',
        duration: 5000,
      });
      shownMessages.current.add(`error:${flash.error}`);
    }

    // Handle info messages
    if (flash?.info && !shownMessages.current.has(`info:${flash.info}`)) {
      toast.info(flash.info, {
        id: 'flash-info',
        duration: 4000,
      });
      shownMessages.current.add(`info:${flash.info}`);
    }

    // Handle warning messages
    if (flash?.warning && !shownMessages.current.has(`warning:${flash.warning}`)) {
      toast.warning(flash.warning, {
        id: 'flash-warning',
        duration: 4000,
      });
      shownMessages.current.add(`warning:${flash.warning}`);
    }

    // Clean up old messages to prevent memory leak
    if (shownMessages.current.size > 50) {
      shownMessages.current.clear();
    }
  }, [flash]);

  return null;
}
