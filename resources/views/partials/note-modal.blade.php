<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="noteForm">
        @csrf
        <input type="hidden" name="notable_type" id="noteType">
        <input type="hidden" name="notable_id" id="noteId">

        <div class="modal-header">
          <h5 class="modal-title">Add Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <textarea name="body" class="form-control" placeholder="Enter your note here..." required></textarea>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Note</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).on('click','.add-note-btn',function(){
    $('#noteType').val($(this).data('type'));
    $('#noteId').val($(this).data('id'));
    $('#noteModal').modal('show');
});

$(document).on('submit','#noteForm',function(e){
    e.preventDefault();
    $.ajax({
        url: "{{ route('notes.store') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            if(res.success){
                let html = `<li>${res.note.body} <small class="text-muted">— You (${res.note.created_at})</small></li>`;
                $('#notes-list').append(html);
                $('#noteModal').modal('hide');
                $('#noteForm')[0].reset();
            }
        },
        error: function(err){
            alert("Error saving note.");
        }
    });
});
</script>
@endpush
