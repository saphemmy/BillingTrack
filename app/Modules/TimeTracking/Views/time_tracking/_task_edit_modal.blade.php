@include('time_tracking._task_list_refresh_js')

<script type="text/javascript">
    $(function() {
        $('#modal-edit-task').modal();

        $('#btn-submit-task').click(function() {
            $.post('{{ route('timeTracking.tasks.update') }}', {
                id: {{ $task->id }},
                time_tracking_project_id: {{ $task->time_tracking_project_id }},
                name: $('#edit_task_name').val()
            }).done(function (response) {
                $('#modal-edit-task').modal('hide');
                refreshTaskList();
            }).fail(function (response) {
                showErrors($.parseJSON(response.responseText).errors, '#modal-status-placeholder');
            });
        });
    })
</script>

<div class="modal fade" id="modal-edit-task">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('fi.edit_task') }}</h4>
            </div>
            <div class="modal-body">

                <div id="modal-status-placeholder"></div>

                    <div class="form-group">
                        <label class="control-label">{{ trans('fi.task') }}:</label>
                        {!! Form::text('name', $task->name, ['id' => 'edit_task_name', 'class' => 'form-control']) !!}
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('fi.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="btn-submit-task">{{ trans('fi.submit') }}</button>
            </div>
        </div>
    </div>
</div>