<template>
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td><input class="form-control" type="text" placeholder="Номер договора" v-model="form.number"></td>
                            <td><input class="form-control" type="text" placeholder="ФИО" v-model="form.fio"></td>
                            <td><input class="form-control" type="date" placeholder="Дата рождения" v-model="form.birth_date"></td>
                            <td style="vertical-align: middle"><input name="files" id="files" ref="files" @change="onHandleChange" class="form-control-file" type="file" ></td>
                            <td><button :disabled="isSubmit" @click="onHandleAddForm" class="btn btn-primary">Добавить</button></td>
                        </tr>
                    </tbody>
                </table>
                <hr>

                    <table class="table table-striped table-padding">
                        <thead class="bg-info">
                        <tr>
                            <td><b>№</b></td>
                            <td><b>ФИО</b></td>
                            <td><b>Дата рождения</b></td>
                            <td><b>Фото</b></td>
                            <td><b>Создано</b></td>
                        </tr>

                        </thead>
                        <tbody>
                            <tr v-for="order in order_list" :index="order.id">
                                <td style="width: 200px;">{{ order['number'] }}</td>
                                <td class="cursor-for-editor" v-on:dblclick="order.isEditFio = true; order.isEditFioPrev = order.fio">
                                    <div v-show="order.isEditFio == false">{{ order['fio'] }}</div>
                                    <div v-show="order.isEditFio == true">
                                        <input  type="text"
                                                placeholder="ФИО"
                                                v-model="order.fio"
                                                @keyup.enter = "order.isEditFio = false; updateElement(order.id, order.fio, 'fio')"
                                                @keyup.esc = "order.isEditFio = false; order.fio = order.isEditFioPrev"
                                        >
                                    </div>
                                </td>
                                <td style="width: 200px" class="cursor-for-editor" v-on:dblclick="order.isEditBirth = true; order.isEditBirthPrev = order.birth_date">
                                    <div v-show="order.isEditBirth == false">{{ order['birth_date'] | fnToLocaleSmall }}</div>
                                    <div v-show="order.isEditBirth == true">
                                        <input type="date"
                                               v-model="order.birth_date"
                                               @keyup.enter = "order.isEditBirth = false; updateElement(order.id, order.birth_date, 'birth_date')"
                                               @keyup.esc = "order.isEditBirth = false; order.birth_date = order.isEditBirthPrev"
                                        >
                                    </div>
                                </td>
                                <td  class="cursor-for-editor" v-on:dblclick="order.isEditPhoto = true; order.isEditPhotoPrev = order.file">
                                    <div v-show="order.isEditPhoto == false">
                                        <img
                                            width="25px"
                                            :src="'images/upload/' + order.file"
                                            alt=""
                                            class="pointer-image"
                                        >
                                    </div>
                                    <div ref="photo" v-show="order.isEditPhoto == true">
                                        <input
                                                type="file"
                                                style="width: 250px;"
                                                name="photo"
                                                id="photo"
                                                @change="updateElementPhoto(order.id, $event)"
                                                @keyup.esc = "order.isEditPhoto = false; order.file = order.isEditPhotoPrev"
                                        >
                                    </div>
                                </td>
                                <td style="width: 150px;">{{ order.created_at }}</td>
                            </tr>
                        </tbody>
                    </table>

            </div>
        </div>

</template>

<script>
    export default {
        data: function(){
            return {
                isSubmit: false,
                order_list: [],
                file_ext: ['png', 'jpeg'],
                form: {
                    number: null,
                    fio: null,
                    photo: null,
                    birth_date: null,
                }
            }
        },
        computed: {
                //
        },
        methods: {
            onHandleAddForm(event){
                const data = new FormData();
                for(let d in this.form) data.append(d, this.form[d]);
                this.isSubmit = true;

                axios.post('add_order',
                    data,
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        this.isSubmit = false;
                        if(+response.data.success){
                            this.order_list = response.data.order.map( item => Object.assign({}, item,{'isEditFio': false, 'isEditBirth': false, 'isEditPhoto': false}));
                            toastr['success']('Договор успешно добавлен.');
                            return;
                        }
                        toastr['error'](response.data.message);
                    })
                    .catch( error =>  {
                        this.isSubmit = false;
                        console.log(error)
                    })
            },
            onHandleChange(event){
                if(!window.File) {
                    const message = "Ваш браузер не поддерживает API файлов";
                    toastr['warning'](message);
                    console.log(message);
                }
                //console.log(JSON.stringify(this.$refs.files.files ))
                this.form.photo = this.$refs.files.files ? this.$refs.files.files[0] : null;
                if(this.form.photo){
                    const valid = this.isValidFile(this.form.photo);
                    if(valid) {
                        this.$refs.files.value = null;
                        return toastr['warning'](valid);
                    }
                }
            },
            onHandleEdit(event){
                //const name = event.target.id;
                //const index = event.target.parentElement.getAttribute('index');
                //this.isEdit[index][name] = true;
            },
            updateElement: function(id, value, field){
                console.log(`id: ${id}, value: ${value}, field: ${field}`);
                axios.post('update_order', {'id': id, 'value': value, 'field': field})
                    .then( response => {
                        if(+response.data.success) return toastr['success']('Успешно сохранил');
                        toastr['error'](response.data.message)
                    })
            },
            updateElementPhoto(id, event){
                if(!window.File) {
                    const message = "Ваш браузер не поддерживает API файлов";
                    toastr['warning'](message);
                    console.log(message);
                }
                let file = event.target.files[0];
                const valid = this.isValidFile(file);
                if(valid) return toastr['warning'](valid);

                const data = new FormData();
                data.append('id', id);
                data.append('file', file);
                axios.post('update_photo',
                    data,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then( response => {
                        if(+response.data.success) {
                            this.order_list = response.data.order.map( item => Object.assign({}, item,{'isEditFio': false, 'isEditBirth': false, 'isEditPhoto': false}));
                            toastr['success']('Успешно сохранил');
                            return
                        }
                        toastr['error'](response.data.message);
                    })

            },
            isValidFile(file){
                let message = null;
                const file_type = file.type.split('/')[1];
                if(!this.file_ext.some( f => f == file_type))  message = 'Тип файла не поддерживаеться';
                if(file.size > (Math.pow(1000,2)*2) ) message = `Файл слишком большой (${Math.round( ( file.size / 1000) / 1000)} Мб)`;
                if(message) toastr['warning'](message);
                return message;
            }
        },
        filters: {
            fnToLocaleSmall(dat){
                if(_.isEmpty(dat)) return;
                return new Date(String(dat.replace(' ','T'))).toLocaleString('ru-RU', {day: 'numeric', month: 'numeric', year: 'numeric'})
            }
        },
        mounted() {
            axios.get('get_order')
                .then(response => {
                    if(+response.data.success){
                        const order = response.data.order.map( item => Object.assign({}, item,{'isEditFio': false, 'isEditBirth': false, 'isEditPhoto': false}));
                        this.order_list = _.extend(order);
                        return;
                    }
                    toastr['error'](response.data.message);
                });
        }
    }
</script>

<style>
    .table-padding {
        padding: 5px;
    }
    .cursor-for-editor {
        cursor: pointer;
    }
</style>
