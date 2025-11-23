<template>
    <div class="col-md-3" id="right-chat">
        <div class="clear-fix"></div>
        <div class="col-md-12" id="box-notification">
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
            <p><span class="text-danger">LongNguyen</span> vừa đăng video.</p>
        </div>
        <div class="col-md-12" id="box-comment">
            <div class="row">
                <div class="col-md-12" id="list-comment">
                    <div v-for="comment in comments" class="row">
                        <div class="comment-item">
                            <div class="comment-content">
                                <p>{{ comment.comment }}</p>
                            </div>
                            <div class="comment-avatar">
                                <img src="../../../public/assets/images/user-pro-img.png" width="80%" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="form-comment">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <input v-model="txt_comment" type="text" class="form-control"
                                    placeholder="Bạn muốn nói gì?" name="comment">
                            </div>
                        </div>
                        <div class="col-md-2 btn-send-cmt">
                            <button v-on:click="sendComment()" class="btn btn-danger">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            comments: [],
            txt_comment: '',
        }
    },
    mounted() {
        this.listComment();
    },
    methods: {
        async joinRoom() {
            this.$socket.emit('ClientSendCommentToServer', {
                // Pass param obj
                transaction_id: 1
            });
        },
        listComment() {
            axios.get('list-comment', {
                params: {
                    "video_id": localStorage.getItem('video_id')
                }
            }).then(res => {
                if (res.data.code == 200) {
                    this.comments = res.data.data
                }
            }).catch(err => {
                console.log(res.data);
            })
        },
        async sendComment() {
            try {
                if (this.txt_comment == '') {
                    alert('vui long nhap comment');
                    return null;
                }
                const callAPI = await axios.post('send-comment', {
                    'video_id': localStorage.getItem('video_id'),
                    'comment': this.txt_comment,
                });
                if (callAPI.data.code == 200) {
                    this.comments.push(callAPI.data.data);
                    this.txt_comment = '';
                }
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>