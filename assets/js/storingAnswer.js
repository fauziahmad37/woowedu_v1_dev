'use strict';


/**
 * Class untuk menyimpan jawaban pada indexed db
 *
 * @class StoringAnswer
 * @typedef {StoringAnswer}
 */
class StoringAnswer {

    db;
    constructor(dbName) {
        this.dbName = dbName;
        this.#init();
    }

    get database() {
        return this.db;
    }

    set database(db) {
        this.db = db;
    }


    #init() {

        const newDB = window.indexedDB.open(this.dbName);

        newDB.onerror = () => new Error("Database tidak dapat ter inisialisasi");
        newDB.onsuccess = () => { 
            console.log("Connected to db");
            this.database = newDB.result;
            
        };
        newDB.onupgradeneeded = () => {
            const table = newDB.result.createObjectStore('answer', { keyPath: 'id', autoIncrement: false });

            table.createIndex('id', 'id', { unique: true });
            table.createIndex('group', 'group', { unique: false });
            table.createIndex('soal_id', 'soal_id', { unique: true });
            table.createIndex('tipe', 'tipe', { unique: false });
            table.createIndex('jawaban', 'jawaban',{ unique: false });

        }

    }

    
    /**
     * Get One answer by id
     *
     * @param {*} obj
     * @returns {*}
     */
    fetchOne(obj) {
        if(!this.database)
            this.#init();
        let fetchData;
        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');
        const res = objStore.get(obj);
        return res;
    }

    fetchAll(opt=null) {
        if(!this.database)
            this.#init();
        let fetchData;
        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');
        const res = objStore.getAll();

        return res;
    }
    
    /**
     * sotre new answer in indexedDB
     *
     * @param {*} obj
     */
    addData(obj) {
        if(!this.database)
            this.#init();
        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');
        objStore.add(obj);

    }

    
    /**
     * Update answer data 
     *
     * @param {*} obj
     */
    updateData(obj) {
        if(!this.database)
            this.#init();
        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');
        objStore.put(obj);
    }

    
    /**
     * Save OR Update answer data
     *
     * @param {*} obj
     */
    saveData(obj) {
        if(!this.database)
            this.#init();
        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');

        const res = objStore.get(obj.id);

        res.onerror = evt => console.error(evt);
        res.onsuccess = evt => {
            console.log(evt);
            if(evt.target.result && evt.target.result.id)
                objStore.put(obj);
            else
                objStore.add(obj);
        }
       

    }

    
    /**
     * Delete an object
     *
     * @param {*} obj
     */
    deleteData(id) {
        if(!this.database)
            this.#init();

        const trans = this.database.transaction('answer', 'readwrite');
        const objStore = trans.objectStore('answer');

        const hapus = objStore.delete(id);

        hapus.onsuccess = evt => {
            console.log('Delete Success');
        }
    }

    truncateDatabase() {
        indexedDB.deleteDatabase(this.dbName);
    }

}