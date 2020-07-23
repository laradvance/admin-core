@admin_assets('fileinput')

<div class="form-group">
    <label>{{ $label }}</label>
    <input type="file" class="{{$class}}" name="{{$name}}" {!! $attributes !!} />
    @include('admin::actions.form.help-block')
</div>

<script require="fileinput">
    var $input = $("input{{ $selector }}");
    $input.fileinput({!! $options !!});

    @if($settings['showRemove'])
    $input.on('filebeforedelete', function() {
        return new Promise(function(resolve, reject) {
            var remove = resolve;
            swal({
                title: "{{ trans('admin.delete_confirm') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('admin.confirm') }}",
                showLoaderOnConfirm: true,
                cancelButtonText: "{{ trans('admin.cancel') }}",
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        resolve(remove());
                    });
                }
            });
        });
    });
    @endif
</script>
