<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid px-0">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Fechar Chamado
                    </h2>
                    <p class="text-muted mb-0">{{ $request->title }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="alert alert-info d-flex align-items-start gap-2 mb-4">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <div>
                                <strong>Como fechar o chamado:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    <li>Descreva como o problema foi resolvido (mínimo 10 letras)</li>
                                    <li>Se possível, envie uma foto ou vídeo mostrando a solução</li>
                                    <li>Após fechar, o chamado será marcado como "Concluído"</li>
                                </ul>
                            </div>
                        </div>

                        <form action="{{ route('requests.close', $request) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Descrição do Fechamento --}}
                            <div class="mb-4">
                                <label for="closure_description" class="form-label fw-bold">
                                    Como o problema foi resolvido? <span class="text-danger">*</span>
                                </label>
                                <textarea name="closure_description" id="closure_description"
                                    class="form-control @error('closure_description') is-invalid @enderror" rows="5"
                                    placeholder="Descreva os passos realizados para resolver o problema..." required
                                    minlength="10">{{ old('closure_description') }}</textarea>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mínimo de 10 caracteres. Seja claro e detalhado.
                                </div>
                                @error('closure_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload de Mídia --}}
                            <div class="mb-4">
                                <label for="closure_media" class="form-label fw-bold">
                                    Foto ou Vídeo da Solução
                                </label>
                                <input type="file" name="closure_media" id="closure_media"
                                    class="form-control @error('closure_media') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.heic,.mp4,.mov,.avi">
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Formatos aceitos:</strong> JPG, PNG, HEIC (fotos) | MP4, MOV, AVI (vídeos)
                                    <br>
                                    <strong>Tamanho máximo:</strong> 20MB (aproximadamente 2 minutos de vídeo)
                                </div>
                                @error('closure_media')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview da Mídia --}}
                            <div id="media-preview" class="mb-4 d-none">
                                <label class="form-label fw-bold">Pré-visualização:</label>
                                <div class="border rounded p-3 bg-light">
                                    <img id="image-preview" class="img-fluid rounded d-none" style="max-height: 300px;">
                                    <video id="video-preview" class="w-100 rounded d-none" style="max-height: 300px;"
                                        controls></video>
                                </div>
                            </div>

                            {{-- Botões --}}
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('requests.show', $request) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Fechar Chamado
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Preview de mídia
            document.getElementById('closure_media').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) {
                    document.getElementById('media-preview').classList.add('d-none');
                    return;
                }

                const preview = document.getElementById('media-preview');
                const imagePreview = document.getElementById('image-preview');
                const videoPreview = document.getElementById('video-preview');

                // Resetar previews
                imagePreview.classList.add('d-none');
                videoPreview.classList.add('d-none');
                imagePreview.src = '';
                videoPreview.src = '';

                // Verificar tamanho
                const maxSize = 20 * 1024 * 1024; // 20MB
                if (file.size > maxSize) {
                    alert('Arquivo muito grande! O tamanho máximo é 20MB.');
                    e.target.value = '';
                    preview.classList.add('d-none');
                    return;
                }

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function (event) {
                    if (file.type.startsWith('image/')) {
                        imagePreview.src = event.target.result;
                        imagePreview.classList.remove('d-none');
                    } else if (file.type.startsWith('video/')) {
                        videoPreview.src = event.target.result;
                        videoPreview.classList.remove('d-none');
                    }
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            // Validação de caracteres mínimos
            const textarea = document.getElementById('closure_description');
            textarea.addEventListener('input', function () {
                const minLength = 10;
                const currentLength = this.value.length;

                if (currentLength < minLength) {
                    this.setCustomValidity(`Mínimo de ${minLength} caracteres. Faltam ${minLength - currentLength}.`);
                } else {
                    this.setCustomValidity('');
                }
            });
        </script>
    @endpush
</x-app-layout>