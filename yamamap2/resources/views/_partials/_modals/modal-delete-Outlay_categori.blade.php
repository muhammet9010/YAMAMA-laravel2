<div class="modal fade" id="deleteItemCardCategory" tabindex="-1" aria-hidden="true">
    <div class="col-6 modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content w-50 m-auto">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-2">
                    <h3 class="mb-2">تأكيد الحذف</h3>
                    <p class="text-muted"></p>
                </div>
                @if (isset($info))
                    <form id="deleteItemCardCategoryForm" method="post"
                        action="{{ route('outlay_categori.delete', $info->id) }}" class="row g-3">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            هل أنت متأكد أنك تريد حذف هذا العنصر؟

                            <input type="hidden" id="id" name="id" value="{{ $info->id }}">
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger me-sm-3 me-1">حذف</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">الغاء</button>
                        </div>
                    </form>
                @else
                    <p>لا توجد بيانات لحذفها.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $('#deleteItemCardCategoryForm').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')

        var modal = $(this)
        modal.find('.modal-body #id').val(id);
    })
</script>
