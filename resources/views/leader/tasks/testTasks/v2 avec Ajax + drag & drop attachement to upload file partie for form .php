                       <!-- Attachments Section -->
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>
                        <!-- Attachments classique x-init="init()"  -->
                        <div class="mb-5 " x-data="taskAttachments()" x-init="init()"  >

                            {{--  <div class="bg-gray-700 border border-gray-600 rounded p-3">
                                <input type="file" name="attachments[]" multiple
                                    class="form-control bg-gray-700 text-white border-gray-500"
                                    accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">
                                <small class="text-gray-400 d-block mt-2">
                                    Multiple files allowed • Max 10MB per file • Formats: images, PDF, Office, ZIP, video
                                 </small>
                            </div> --}}
                             <!-- Attachments with ajax.js + drap & drop || x-data="attachments()" -->

                            <div  class="border border-gray-600 rounded-lg p-4 bg-gray-700">


                                <!--
                                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center cursor-pointer hover:border-primary transition"
                                    @ dragover.prevent
                                    @ dragenter.prevent="dragOver = true"
                                    @ dragleave.prevent="dragOver = false"
                                    @ drop.prevent="handleDrop($event)">
                                        <p class="text-lg mb-2">Drag & drop files here</p>
                                        <p class="text-sm">or click to browse</p>
                                        <p class="text-gray-500 mt-2 text-xs">Max 10MB per file • Images, PDF, Office, ZIP, video...</p>

                                -->
                                <label for="upload" class="form-label fw-semibold"><i class="fas fa-paperclip me-2"></i>Upload files</label>
                                <!-- Zone de drap & drop -->
                                <div class="border-2 border-dashed rounded-xl p-12 text-center cursor-pointer transition-all duration-300 bg-gray-700/50"
                                    :class="dragOver ? 'border-primary bg-primary/20 shadow-2xl shadow-primary/30' : 'border-gray-600 hover:border-primary'"
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent="dragOver = true"
                                    @dragenter.prevent="dragOver = true"
                                    @dragleave.prevent="dragOver = false"
                                    @drop.prevent="onDrop($event); dragOver = false">

                                    <input id="upload" type="file" x-ref="fileInput" class="hidden" multiple @change="onFileChange($event)">

                                    <div :class="dragOver ? 'text-primary' : 'text-gray-400'">
                                        <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
                                        <p class="text-lg font-bold mb-3">Drag & drop files here</p>
                                        <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
                                        <p class="text-xs mt-3 text-gray-500">
                                            Max 10MB per file • Images, PDF, Office, ZIP, video...
                                        </p>
                                    </div>
                                </div>

                                <!-- Liste des fichiers uploadés -->
                                <div class="mt-8 space-y-4" x-show="files.length > 0">
                                    <template x-for="file in files" :key="file.path">
                                        <div class="flex items-center justify-between bg-gray-800 p-5 rounded-xl border border-gray-700 hover:bg-gray-750 transition">
                                           <div class="flex items-center gap-5 ">
                                                <i class="fas fa-file-all me-3 text-primary text-3xl"></i>
                                                <div>
                                                    <p class="font-medium font-semibold text-lg " x-text="file.name"></p>
                                                    <p class="text-xs text-gray-400">Uploaded successfully</p>
                                                </div>
                                            </div>
                                            <button type="button"  @click="removeFile(file)"
                                                    class="text-danger hover:text-red-400 p-3 rounded-lg bg-gray-700 hover:bg-gray-600 transition">
                                                <i class="fas fa-times text-xl"></i> <i class="fas fa-trash-alt text-xl"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <!-- Aucun fichier Msg -->
                            <div class="text-center py-10 text-gray-500 mt-6" x-show="files.length === 0 && !dragOver">
                                <i class="fas fa-paperclip text-5xl mb-4 opacity-40"></i>
                                <p class="text-xl">No attachments yet</p>
                            </div>

                            @error('attachments.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>



   <!--Scriipt :: Alpine.js pour gérer les uploads en Ajax :: Version finale avec attachments + Ajax -->

    <script>
        // === 1. Enregistrement correct du composant Alpine ===

        // === 1. Enregistrement correct du composant Alpine ===

            //function taskAttachments() {
            //    return {
            document.addEventListener('alpine:init', () => {
                Alpine.data('taskAttachments', () => ({

                    files: [],
                    dragOver: false,

                    init() {
                        // Rien de spécial en create
                        console.log('Attachments component initialized--Attachments Alpine component --Drag & Drop-- ready');
                    },
                    /*
                    onDragOver(e) {
                        e.preventDefault();
                        this.dragOver = true;
                        console.log('Drag over - Border should change');
                    },

                    onDragEnter(e) {
                        e.preventDefault();
                        this.dragOver = true;
                        console.log('Drag enter');
                    },

                    onDragLeave(e) {
                        e.preventDefault();
                        this.dragOver = false;
                        console.log('Drag leave - Border reset');
                    },

                    onDrop(e) {
                        e.preventDefault();
                        this.dragOver = false;
                        this.handleDrop(e);
                        console.log('File dropped - Starting upload');
                    },

                    handleDrop(e) {
                        this.uploadFiles(Array.from(e.dataTransfer.files));
                    },

                    onFileChange(e) {
                        this.uploadFiles(Array.from(e.target.files));
                        console.log('Files selected via click - Starting upload');
                    }, */

                    onFileChange(event) {
                        this.uploadFiles(Array.from(event.target.files));
                    },

                    onDrop(event) {
                        this.uploadFiles(Array.from(event.dataTransfer.files));
                    },

                    uploadFiles(filesList) {
                        filesList.forEach(file => {
                            console.log('Starting upload : ', file.name , 'Size:', file.size);

                            // Vérification taille
                            if (file.size > 10485760) { //}> 10 * 1024 * 1024) { //10MB
                                alert(`"${file.name}" exceeds 10MB limit`);
                                return;
                            }

                            const formData = new FormData();  //let
                            formData.append('file', file);

                            fetch('{{ route('leader.tasks.temp-upload') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    //'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok){
                                    console.error('Upload failed with status:', response.status);
                                    throw new Error('HTTP ' + response.status); // Error('Upload failed');
                                }
                                return response.json();
                            })
                        .// then(response => response.json())
                            .then(data => {
                                /*console.log('Upload success:', data);
                                this.files.push({
                                    name: data.name,
                                    path: data.path,
                                    url: data.url
                                });
                                */
                                if (data.success) {
                                    this.files.push({
                                        name: data.name,
                                        path: data.path,
                                        url: data.url
                                    });
                                    console.log('Uploaded successfully :', data.name);
                                } else {
                                    alert('Upload failed: ' + file.name);
                                }
                            })
                            .catch(err => {
                                console.error('Upload error (js):' , err);
                                alert('Error uploading : ' + file.name);
                            });
                        });
                    },

                    /*removeFile(index) {
                        this.files.splice(index, 1);
                    }*/
                    removeFile(fileToRemove) {
                        this.files = this.files.filter(f => f.path !== fileToRemove.path);
                        console.log('File removed from list :', fileToRemove.name);
                    }
                }));
            });
            // === 2. Tes autres scripts (subtasks, markdown, etc.) ===




  {{-- -  <script> // ce script pointe vers une route (upload-attachment avec {task}).
        document.addEventListener('alpine:init', () => {
            Alpine.data('attachments', () => ({
                files: [],

                handleFiles(event) {
                    const uploaded = Array.from(event.target.files);
                    this.uploadFiles(uploaded);
                },

                handleDrop(event) {
                    const uploaded = Array.from(event.dataTransfer.files);
                    this.uploadFiles(uploaded);
                },

                uploadFiles(files) {
                    files.forEach(file => {
                        if (file.size > 10 * 1024 * 1024) {
                            alert(`File ${file.name} is too large (max 10MB)`);
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', file);

                        fetch('{{ route('leader.tasks.upload-attachment', $task ?? '') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.files.push({
                                    name: file.name,
                                    path: data.path,
                                    url: data.url
                                });
                            } else {
                                alert('Upload failed for ' + file.name);
                            }
                        })
                        .catch(() => alert('Error uploading ' + file.name));
                    });
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                }
            }));
        });
    </script>  --}}
