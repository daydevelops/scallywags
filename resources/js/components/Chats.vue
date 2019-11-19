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
              <h4 
                :class="chat.has_new ? 'updated chat-name' : 'chat-name'" 
                v-text="chat.friend.name" 
                @click="showChat(chat)"
              ></h4>
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
              <button class="btn btn-sm btn-primary" @click="sendMsg(current_chat.id)">Send</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

window.Echo = new Echo({
  broadcaster: "pusher",
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  encrypted: true
});

export default {
  props: ["chats"],
  data() {
    return {
      current_chat:0,
      messages: [],
      new_msg: {
        body: ""
      },
      errors: ""
    };
  },
  created: function() {
    if (this.chats.length > 0) {
      this.showChat(this.chats[0]);
    }
    this.chats.forEach(chat => {
      // listen to pusher channel for each chat
      window.Echo.private(chat.channel_name).listen("NewMessage", e => {
        console.log('new message');
        this.processMessage(e);
      });
      chat.has_new = false; // to-do: this is a bad assumption
    });
  },
  methods: {
    isMyMsg(msg) {
      return window.App.user.id == msg.user_id;
    },
    showChat(chat) {
      this.messages = chat.messages;
      this.current_chat = chat;
      chat.has_new=false;
    },
    sendMsg(chat_id) {
      var endpoint = location.pathname + "/" + chat_id + "/messages";
      axios.post(endpoint, { body: this.new_msg.body }).then(
        response => {
          // no need to add message to chat here
          // we will get an update from pusher which will call processMessage()
          console.log(response.data);
          this.new_msg.body="";
        },
        error => {
          this.errors = error.response.data;
        }
      );
    },
    scrollMsgsToBottom() {
      var msg_box = document.querySelector("#messages");
      msg_box.scrollTop = msg_box.scrollHeight;
    },
    processMessage(event) {
      // process the incoming message from pusher
      this.addMsgToChat(event.data);
      // if the user is currently looking at the updated chat
      if (this.current_chat.id == event.data.chat_id) {
        this.showChat(this.current_chat);
        this.scrollMsgsToBottom();
      } else {
        this.chats.find(c => c.id === event.data.chat_id).has_new=true;
      }
    },
    addMsgToChat(msg) {
      this.chats.find(c => c.id === msg.chat_id).messages.push(msg);
    }
  }
};
</script>
<style scoped>
.chat-li {
  background-color: rgba(0, 0, 0, 0.05);
  margin: 3px;
  padding: 5px;
  border-radius: 15px;
}
.chat-li:hover {
  cursor: pointer;
  background-color: rgba(0, 0, 0, 0.1);
}
.chat-name-wrapper {
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.chat-name.updated {
  font-weight:bold;
}
.chat-img {
  height: 60px;
  width: 60px;
  border-radius: 50%;
  margin: auto;
  display: block;
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
.updated {
  font-weight:bold;
}
</style>