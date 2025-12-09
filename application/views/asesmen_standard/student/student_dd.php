<script id="student-porkas-question" type="text/x-handlebars-template">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="position-relative rounded border border-white d-flex flex-nowrap align-items-center bg-primary-600 p-2" style="height: 140px">
                    {{#if question_file}}
                    <img src="{{adminUrl question_file}}" class="img-fluid overflow-hidden rounded shadow" style="height: calc(140px - 2px)">
                    {{/if}}
                    <h5 class="text-question d-block position-absolute top-50 start-50 text-white" ><div class="d-inline-flex align-items-center flex-wrap">{{unsafe question}}</div></h5>
                    <small class="position-absolute top-0 start-50 bg-primary fw-semibold rounded-pill text-white nomor" >question <span>{{nomor}}/{{totalSoal}}</span></small>
                </div>
            </div>
            <div class="col-12">
                <div class="position-relative rounded row mx-1 justify-content-center align-items-center bg-primary-600 p-3" 
                     style="height: 280px"
                     ondragover="ddContainerDragOver(event)"
                     ondrop="ddContainerDrop(event)">
                    <div class="col-8 d-flex justify-content-center align-items-center" >
                        {{#each answers}}
                        <div class="bg-primary-700 dragbox-container mx-2" data-index="{{@index}}">
                            <span class="py-2 px-3 dragbox rounded bg-white text-dark d-inline" 
                                data-index="{{@index}}" 
                                data-key="{{key}}"
                                draggable="true"
                                ondragstart="ddDragStart(event)" ondragend="ddDragEnd(event)">{{value}}</span>
                        </div>
                        {{/each}}
                    </div>
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
                        <span class="d-inline-block ms-3 fs-5 text-capitalize">Seret / Lepas</span>
                    </span>
                    <button type="button" class="ms-auto btn btn-next-question d-none" id="btn-dd-keluar" onclick="finishExam(event)" disabled>Akhiri <i class="bi bi-chevron-compact-right"></i></button>
                    <button type="button" class="ms-auto btn btn-next-question d-none" id="btn-dd-next" onclick="nextPage(event)" disabled>Selanjutnya <i class="bi bi-chevron-compact-right"></i></button>
                    <button type="button" class="ms-auto btn btn-next-question" id="btn-dd-submit" onclick="submitDragdropQuestion(event)" disabled>Kirim Jawaban <i class="bi bi-chevron-compact-right"></i></button>
                    <h3 class="d-inline-block position-absolute top-100 start-50 translate-middle text-white" id="text-dd-submit"></h3>
                </div>
            </div>
        </div>
      
        
    </div>
</script>