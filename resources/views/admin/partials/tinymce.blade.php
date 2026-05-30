@once
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key', 'no-api-key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.tinymce) return;

            tinymce.init({
                selector: '.rich-editor',
                height: 420,
                menubar: false,
                plugins: 'lists link image table code media autoresize',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media table | code',
                smart_paste: true,
                extended_valid_elements: 'iframe[src|width|height|title|frameborder|allow|allowfullscreen|loading|referrerpolicy|style],video[controls|width|height|poster|preload],source[src|type],img[src|alt|width|height|title|style]',
                content_style: 'body { font-family: Inter, sans-serif; font-size: 16px; line-height: 1.7; }',
                skin: 'oxide',
                branding: false,
                promotion: false
            });

            document.addEventListener('submit', () => {
                tinymce.triggerSave();
            }, true);
        });
    </script>
@endonce
