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
  props: ["initial_chats"],
  data() {
    return {
      chats: this.initial_chats,
      current_chat: 0,
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
    // listen to pusher channel for this user
    window.Echo.private("chat-user-" + window.App.user.id).listen(
      "NewMessage",
      e => {
        console.log("new message");
        this.processMessage(e);
      }
    );
  },
  methods: {
    isMyMsg(msg) {
      return window.App.user.id == msg.user_id;
    },
    showChat(chat) {
      this.messages = chat.messages;
      this.current_chat = chat;
      chat.has_new = false;
      axios.post(location.pathname + "/" + chat.id + "/read");
    },
    sendMsg(chat_id) {
      var endpoint = location.pathname + "/" + chat_id + "/messages";
      axios.post(endpoint, { body: this.new_msg.body }).then(
        response => {
          // no need to add message to chat here
          // we will get an update from pusher which will call processMessage()
          console.log(response.data);
          this.new_msg.body = "";
          this.moveToTop(chat_id);
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

      // if the user is currently looking at the updated chat
      if (this.current_chat.id == event.data.chat_id) {
        this.showChat(this.current_chat);
        this.scrollMsgsToBottom();
      } else {
        var chat_index = this.chats.findIndex(c => c.id === event.data.chat_id);
        if (chat_index == -1) {
          // recieved a message for a chat not shown on this page?
          // must be a newly initiated chat from someone
          var new_chat = axios
            .get(location.pathname + "/" + event.data.chat_id)
            .then(
              response => {
                // no need to add message to chat here
                // we will get an update from pusher which will call processMessage()
                var new_chat = response.data;
                new_chat.has_new = true;
                this.chats.push(new_chat);
                this.moveToTop(new_chat.id);
              },
              error => {
                location.reload();
              }
            );
        } else {
          this.chats[chat_index].has_new = true;
        }
      }

      this.addMsgToChat(event.data);
    },
    addMsgToChat(msg) {
      this.chats.find(c => c.id === msg.chat_id).messages.push(msg);
    },
    moveToTop(chat_id) {
      var chat = this.chats.find(c => c.id === chat_id);
      this.chats = this.chats.filter(c => c.id !== chat_id);
      this.chats.unshift(chat);
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
  font-weight: bold;
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
  font-weight: bold;
}
</style>