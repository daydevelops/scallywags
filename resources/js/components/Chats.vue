<template>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <h4 class="text-center">Recent</h4>
        <div id="chats">
          <div v-for="(chat) in chats" :key="chat.id" class="chat-li row">
            <div class="col-3 p-0">
              <img class="chat-img" :src="chat.friend.image" alt="friend profile image" />
            </div>
            <div class="col-9 chat-name-wrapper">
              <h4 class="chat-name" v-text="chat.friend.name" @click="showChat(chat)"></h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div id="msg-wrapper">
          <div id="messages">
            <div
              v-for="(msg) in this.messages"
              :key="msg.id"
              :class="isMyMsg(msg) ? 'my-msg msg' : 'friend-msg msg'"
              v-text="msg.body"
            ></div>
          </div>
          <div id="new-msg-form">
            <div class="form-group">
              <p class="text-center" v-text="errors"></p>
              <textarea class="form-control" v-model="new_msg.body"></textarea>
              <button class="btn btn-sm btn-primary" @click="sendMsg(chat.id)">Send</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["chats"],
  data() {
    return {
      messages: [],
      new_msg: {
        body: ""
      },
      errors: ""
    };
  },
  mounted: function() {
    if (this.chats.length > 0) {
      this.showChat(this.chats[0]);
    }
  },
  methods: {
    isMyMsg(msg) {
      return window.App.user.id == msg.user.id;
    },
    showChat(chat) {
      this.messages = chat.messages;
      this.scrollMsgsToBottom();
    },
    sendMsg(chat_id) {
      var endpoint = location.pathname + "/" + chat_id + "/messages";
      axios.post(endpoint, { body: this.new_msg.body }).then(
        response => {
          console.log(response.data);
        },
        error => {
          this.errors = error.response.data;
        }
      );
    },
    scrollMsgsToBottom() {
      var msg_box = document.querySelector("#messages");
      msg_box.scrollTop = msg_box.scrollHeight;
    }
  }
};
</script>
<style scoped>
.chat-li {
    background-color:rgba(0,0,0,0.05);
    margin:3px;
    padding:5px;
    border-radius:15px;
}
.chat-li:hover {
    cursor:pointer;
    background-color:rgba(0,0,0,0.10);
}
.chat-name-wrapper {
    display:flex;
    flex-direction:column;
    justify-content: center;
}
.chat-img {
    height:60px;
    width:60px;
    border-radius:50%;
    margin:auto;
    display:block;
}
#messages {
  max-height: 75vh;
  overflow-y: scroll;
}
.msg {
  width: 90%;
  border-radius: 20px;
  padding: 15px;
  margin: 5px 0px;
}
.my-msg {
  margin-left: auto;
  background-color: lightblue;
}
.friend-msg {
  margin-right: auto;
  background-color: palegreen;
}
</style>