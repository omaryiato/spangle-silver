-- Proposal only. Apply manually before enabling customer cancellation.
-- Preserve legacy status 0 until existing data has been reviewed.
BEGIN;
ALTER TABLE public.orders DROP CONSTRAINT chk_ord_status;
ALTER TABLE public.orders ADD CONSTRAINT chk_ord_status CHECK (status IN (0, 1, 2, 3, 4, 5, 6));
COMMIT;
