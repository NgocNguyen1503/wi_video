<template>
    <div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="report-modal">Báo cáo vi phạm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="report-content">Nội dung báo cáo (<span class="text-danger">*</span>) </label>
                    <textarea name="report" class="form-control" v-model="reportContent" id="report-content"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" v-on:click="sendReport()">Gửi báo cáo</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import Vue from 'vue'
//import axios from 'axios'
// import component1 from 'component1'
// import component2 from 'component2'

export default {
    /***********************************************************************************************************
     ******************************* Pass data to child component **********************************************
     **********************************************************************************************************/
    props: ["videoId"],
    // components: {component1, component2},
    data() {
        /***********************************************************************************************************
         ******************************* Initialize global variables ***********************************************
         **********************************************************************************************************/
        return {
            msg: 'Tôi là thành phần con.',
            reportContent: ''
        }
    },
    /**
     * Define global service socket
     *
     * Listing event from socket.io server
     * "ServerSendCommentToClient" is the name of the channel that sends notifications to all clients installed in the server socket
     */
    sockets: {
        // Send data to server
        ClientSendCommentToServer: function (responseComment) {
            this.comment = responseComment;
        },
        // Listen event from server and render data
        ServerSendCommentToClient: function (responseComment) {
            // Push to the comment list
            if (responseComment.type === 'comment' && this.transaction.id == responseComment.transaction_id) {
                this.pushCommentToList(responseComment);
                this.$forceUpdate();
            }
        },
    },
    created() {
        /***********************************************************************************************************
         *********************** Initialize data when this component is used. **************************************
         **********************************************************************************************************/
        console.log('Init created component and call to function get data from api server.');
        // this.joinRoom();
    },
    mounted() {
        /***********************************************************************************************************
         ******************** Once created, the interface is displayed and calls mounted. **************************
         **********************************************************************************************************/
        // this.callAPI();
    },
    watch: {
        /***********************************************************************************************************
         ********************************* Methods change value for a variable *************************************
         **********************************************************************************************************/
        msg() {
            console.log("When the value of the msg variable changes, this method will be executed.");
        }
    },
    computed: {
        appendMsg() {
            return msg + "Process the value and assign the value to the corresponding variable the var has changed.";
        }
    },
    methods: {
        /***********************************************************************************************************
         ******************************* Default functions that handle local data **********************************
         **********************************************************************************************************/

        /**
         * Example default function not using param
         */
        defaultFunction() {
            this.msg = "Replace message here!";
        },
        // Join a room
        joinRoom() {
            this.$socket.emit('ClientSendCommentToServer', {
                // Pass param obj
                transaction_id: 1
            });
        },
        /**
         * Example default function using param
         *
         * @param int pageNum number of page
         * @return boolean
         */
        defaultFunctionUsingParam(pageNum) {
            console.log(pageNum);
            return false;
        },
        /***********************************************************************************************************
         ******* Async and await functions for manipulating server-side data through internal API protocols ********
         **********************************************************************************************************/

        /**
         * Call API sample
         */
        async sendReport() {
            try {
                const callAPI = await axios.post('http://localhost:8000/send-report', {
                    /************ Attach param for request here ***************/
                    video_id: this.videoId,
                    report_content: this.reportContent
                });
                console.log(callAPI.data.data);
            } catch (err) {
                console.log(err);
            }
        },
    },
}
</script>