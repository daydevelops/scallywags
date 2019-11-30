<template>
  <div class="container">
    <div class="row" v-if="chats.length>0">
      <div class="col-md-3">
        <h4 class="text-center">Recent</h4>
        <div id="chats">
          <div
            v-for="(chat) in chats"
            :key="chat.id"
            class="chat-li row p-2 m-1 border border-dark"
          >
            <div class="col-3 p-0">
              <img
                class="chat-img m-auto d-block rounded-circle"
                :src="chat.friend.image"
                alt="friend profile image"
              />
            </div>
            <div class="col-9 chat-name-wrapper d-flex flex-column justify-content-center">
              <h4
                :class="chat.has_new ? 'font-weight-bold chat-name' : 'chat-name'"
                v-text="chat.friend.name"
                @click="showChat(chat)"
              ></h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <h3
          class="chat-name text-center"
          v-if="this.current_chat"
          v-text="this.current_chat.friend.name"
        ></h3>
        <div id="msg-wrapper">
          <div id="messages" class="d-flex flex-column p-3 mb-3">
            <div
              v-for="(msg) in this.messages"
              :key="msg.id"
              :class="isMyMsg(msg) ? 'my-msg msg text-right ml-auto' : 'friend-msg msg text-left mr-auto'"
              @click="toggleTimestamp(msg)"
            >
              <div v-text="msg.body"></div>
              <div :ref="'timestamp-'+msg.id" class="timestamp hidden"></div>
            </div>
          </div>

          <div id="new-msg-form">
            <div class="form-group d-flex align-items-center">
              <textarea id="new-msg-body" class="form-control" v-model="new_msg.body"></textarea>
              <button
                id="new-msg-btn"
                class="btn btn-sm btn-primary"
                @click="sendMsg(current_chat.id)"
              >Send</button>
            </div>
          </div>
          <p class="text-center" v-text="errors"></p>
        </div>
      </div>
    </div>
    <div class="text-center" v-else>
      <h3>Looks like you don't have any conversations yet.</h3>
      <p>Visit a users profile to initiate a chat!</p>
    </div>
  </div>
</template>


<script>
import Echo from "laravel-echo";

window.moment = require("moment-timezone/moment-timezone");

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
      current_chat: null,
      messages: [],
      new_msg: {
        body: ""
      },
      errors: ""
    };
  },
  created: function() {
    this.chats.forEach(chat => {
      // listen to pusher channel for each chat
      window.Echo.private("chat-"+chat.id).listen("NewMessage", e => {
        console.log('new message');
        this.processMessage(e);
      });
      chat.has_new = false; // to-do: this is a bad assumption
    })
  },
  mounted() {
    this.$nextTick(() => {
      if (this.chats.length > 0) {
        this.showChat(this.chats[0]);
      }
    });
  },
  methods: {
    isMyMsg(msg) {
      return window.App.user.id == msg.user_id;
    },
    showChat(chat) {
      this.current_chat = chat;
      this.messages = chat.messages;
      chat.has_new = false;
      this.$nextTick(() => {
        this.scrollMsgsToBottom();
      });
      axios.post(location.pathname + "/" + chat.id + "/read");
    },
    toggleTimestamp(msg) {
      var ts = this.$refs["timestamp-" + msg.id][0];
      ts.classList.toggle("hidden");
      ts.innerHTML = moment
        .utc(msg.created_at)
        .local()
        .calendar();
    },
    sendMsg(chat_id) {
      if (this.new_msg.body == "") {
        return false;
      }
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
          this.errors = "There was a problem sending your message";
        }
      );
    },
    scrollMsgsToBottom() {
      var msg_box = document.querySelector("#messages");
      msg_box.scrollTop = msg_box.scrollHeight;
    },
    processMessage(event) {
      // process the incoming message from pusher
      // event.data is the message object

      // if the user is currently looking at the updated chat
      if (this.current_chat && this.current_chat.id == event.data.chat_id) {
        axios.post(location.pathname + "/" + event.data.chat_id + "/read");
        this.addMsgToChat(event.data);
        if (event.data.user_id == window.App.user.id) {
          // this user sent this message, so scroll to the bottom
          this.scrollMsgsToBottom();
        }
      } else {
        // a chat in the chat list needs to be updated
        var chat_index = this.chats.findIndex(c => c.id === event.data.chat_id);
        this.addMsgToChat(event.data);
        this.chats[chat_index].has_new = true;
      }

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
  border-radius: 15px;
}
.chat-li:hover {
  cursor: pointer;
  background-color: rgba(0, 0, 0, 0.1);
}
.chat-img {
  height: 60px;
  width: 60px;
}
#chats {
  height: calc(60vh + 80px + 1rem);
  overflow-y: scroll;
  background-color: rgba(0, 0, 0, 0.05);
}
#messages {
  background-color: rgba(0, 0, 0, 0.05);
  height: 60vh;
  overflow-y: scroll;
}
.msg {
  max-width: 75%;
  border-radius: 20px;
  padding: 15px;
  margin: 5px 0px;
  border: 1px solid black;
  cursor: pointer;
}
.my-msg {
  background-color: rgb(213, 255, 255);
}
.friend-msg {
  background-color: #dfd;
}
#new-msg-form {
  height: 80px;
}
#new-msg-body {
  flex: 1;
  height: 80px;
}
#new-msg-btn {
  margin: 0px 15px;
}
.timestamp {
  font-size: 12px;
}
.timestamp.hidden {
  display: none;
}
</style>