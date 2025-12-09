<script id="student-pairing-question" type="text/x-handlebars-template">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="position-relative rounded border border-white d-flex flex-nowrap align-items-center bg-primary-600 p-2" style="height: 140px">
                    {{#if question_file}}
                    <img src="{{adminUrl question_file}}" class="img-fluid overflow-hidden rounded shadow" style="height: calc(140px - 2px)">
                    {{/if}}
                    <h5 class="text-question d-block position-absolute top-50 start-50 text-white">{{question}}</h5>
                    <small class="position-absolute top-0 start-50 bg-primary fw-semibold rounded-pill text-white nomor" >question <span>{{nomor}}/{{totalSoal}}</span></small>
                </div>
            </div>
            <div class="col-12">
                <div class="position-relative rounded row mx-1 justify-content-center bg-primary-600 p-3" style="height: 380px">
                    {{#each constipasi}}
                    <div class="col-3 box-answer" id="indukSemang-{{@index}}">
                        <div class="box-answer-head mb-3 bg-primary-700 rounded" style="height: 160px;">
                            <div class="d-flex draggable-box justify-content-center align-items-center overflow-hidden bg-white text-dark rounded p-2 w-100 position-relative" 
                                 draggable="true" 
                                 ondragstart="pairingDragStart(event)"
                                 ondrag="pairingDragMove(event)"
                                 ondragend="pairingDragEnd(event)"
                                 style="height: 160px;"
                                 id="draggable-{{@index}}"
                                 data-draggable-sort="{{key.index}}"
                                 data-value="{{key.text}}">
                                <span class="d-flex flex-nowrap bg-white position-absolute top-0 left-0 w-100 px-3" style="z-index: 200">
                                    <i class="bi bi-grid-3x2-gap-fill ms-auto fs-4 text-primary" style="cursor: grab"></i>
                                    <a role="button" class="text-primary remove-dropped text-decoration-none d-none ms-2 fs-4" onclick="removeDroppedPairing(event)">&times;</a>
                                </span>
                                {{#if key.isImage}}
                                <img src="{{adminUrl key.text}}" class="img-fluid mt-3" style="height: 140px"/>
                                {{else}}
                                <h4 class="mb-0 text-primary fw-semibold">{{key.text}}</h4>
                                {{/if}}
                            </div>
                        </div>
                        <div class="box-answer-body p-2 bg-primary-400 rounded">
                            <div class="box-answer-drop d-flex justify-content-center align-items-center rounded bg-primary-600" 
                                 ondragover="pairingDragOver(event)" 
                                 ondragleave="pairingDragLeave(event)" 
                                 ondrop="pairingDrop(event)" style="height: 160px"
                                 id="dropzone-{{@index}}"
                                 data-dropzone-sort="{{value.index}}"
                                 data-value="{{value.text}}">
                                <h4>{{value.text}}</h4>
                            </div>
                        </div>
                        
                    </div>
                    {{/each}}
                </div>
            </div>
            <div class="col-12">
                <div class="w-100 bg-primary-600 rounded position-relative d-flex flex-nowrap align-items-stretch p-3 soal-footer mt-3">
                    <span class="d-block border-end border-white ps-2 pe-4">
                        <span class="fw-semibold fs-6">{{title}}</span><br/>
                        <small>{{subject_name}}&nbsp;-&nbsp;{{class_name}}</small>
                    </span>
                    <span class="d-flex align-items-center px-3">
                        <i class="bi bi-stack text-white fs-4"></i> 
                        <span class="d-inline-block ms-3 fs-5 text-capitalize">menjodohkan</span>
                    </span>
                    
                    <button type="button" class="ms-auto btn btn-next-question d-none" id="btn-pairing-keluar" onclick="finishExam(event)" disabled>Akhiri <i class="bi bi-chevron-compact-right"></i></button>
                    <button type="button" class="ms-auto btn btn-next-question next-soal d-none" id="btn-pairing-next" onclick="nextPage(event)" disabled>Selanjutnya <i class="bi bi-chevron-compact-right"></i></button>
                    <button type="button" class="ms-auto btn btn-next-question" id="btn-pairing-submit" onclick="submitPairingQuestion(event)" disabled>Kirim Jawaban <i class="bi bi-chevron-compact-right"></i></button>
                    <h3 class="d-inline-block position-absolute top-100 start-50 translate-middle text-white" id="text-submit-status"></h3>
                </div>
            </div>
        </div>
      
        
    </div>
</script>