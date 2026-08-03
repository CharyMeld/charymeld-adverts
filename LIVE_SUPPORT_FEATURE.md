# Live Support Chat Feature

## Overview
The AI chatbot now supports seamless handoff to live human support agents when users need real assistance!

## How It Works

### For Users:
1. **Start chatting** with the AI assistant via the bottom-right chat button
2. **Ask for support** by saying things like:
   - "connect me to support"
   - "I need to talk to a human"
   - "connect me to a support agent"
   - "speak to someone"
   - Or ask about contact/support, and the AI will offer to connect you

3. **Get connected** - The AI will immediately notify the support team and update the chat header to show you're being connected

4. **Chat live** - Once a support agent joins, you'll see:
   - Header changes to "Live Support Team"
   - Green indicator showing you're connected to a real person
   - All your messages go directly to the support agent

### For Support Team (Admins):
1. **Access Support Dashboard** - Navigate to "Support Chat" in the admin menu

2. **View Pending Requests** - See all users waiting for support with:
   - User information
   - Latest message preview
   - How long they've been waiting

3. **Respond to Requests** - Click "Respond" to:
   - Automatically connect to the conversation
   - Send personalized messages
   - View full conversation history

4. **Active Chats** - Manage ongoing support conversations:
   - See which agent is handling each conversation
   - Jump into any active chat
   - Continue conversations seamlessly

5. **Resolve Tickets** - Mark conversations as resolved when done:
   - Sends automatic closing message
   - Moves to resolved history
   - User can start a new conversation if needed

## Key Features

### AI Detection
- Intelligent keyword matching detects support requests
- Patterns like "connect to support", "talk to human", "need help from person"
- Automatic status updates and notifications

### Real-time Handoff
- Instant status changes from AI → Requested → Connected
- Visual indicators for users (yellow for waiting, green for connected)
- No interruption in conversation flow

### Support Dashboard
- **3 status tabs:**
  - Pending Requests (waiting for response)
  - Active Chats (currently being handled)
  - Resolved (completed tickets)

- **Statistics:**
  - Pending request count
  - Active chat count
  - Resolved today count

### Message Types
The system supports 3 message sender types:
- **AI**: Purple icon, automated responses
- **User**: Gray icon, customer messages
- **Support**: Primary color icon, human agent messages

## Database Structure

### New Fields in `chat_conversations`:
- `support_status`: Enum ('ai_only', 'requested', 'connected', 'resolved')
- `support_user_id`: ID of the admin handling the chat
- `support_requested_at`: Timestamp when support was requested
- `support_connected_at`: Timestamp when agent connected

### Message Sender Types:
- `user`: Customer messages
- `ai`: AI assistant responses
- `support`: Human support agent messages

## Routes

### User Routes:
- `/assistant` - Chat interface (floating widget)
- `/assistant/conversations/{id}/messages` - Send/receive messages

### Admin Routes:
- `/admin/support-chat` - Support dashboard
- `/admin/support-chat/{conversation}` - View conversation
- `/admin/support-chat/{conversation}/connect` - Connect to conversation
- `/admin/support-chat/{conversation}/messages` - Send support messages
- `/admin/support-chat/{conversation}/resolve` - Mark as resolved

## Example Conversation Flow

1. **User**: "How do I post an advert?"
   - **AI**: [Provides instructions]

2. **User**: "I'm having trouble, can you connect me to support?"
   - **AI**: "✅ **Support Request Received!** I've notified our support team..."
   - Status changes to "requested"
   - Appears in admin "Pending Requests" tab

3. **Admin**: Clicks "Respond" and joins conversation
   - Status changes to "connected"
   - Automatic greeting sent: "👋 Hi! I'm [Admin Name] from the CharyMeld support team..."

4. **User & Support**: Live conversation
   - Messages go directly between user and support agent
   - No AI interference

5. **Admin**: Clicks "Mark as Resolved"
   - Status changes to "resolved"
   - Automatic closing message sent
   - Moves to "Resolved" tab

## Benefits

✅ **Seamless Experience** - No need to leave the chat or switch platforms
✅ **Instant Escalation** - From AI to human in seconds
✅ **Full Context** - Support agents see entire conversation history
✅ **Professional Interface** - Modern, polished UI for both users and agents
✅ **Smart Detection** - AI automatically recognizes when users need human help
✅ **Organized Dashboard** - Easy to manage multiple support requests
✅ **Status Tracking** - Always know who's helping whom and current status

## Testing the Feature

### As a User:
1. Log in as a regular user (advertiser@demo.com / Demo@123)
2. Click the chat button in bottom-right corner
3. Type: "connect me to support"
4. See the status change to "Connecting to support team..."

### As Admin:
1. Log in as admin (admin@charymeld.com / Admin@123)
2. Click "Support Chat" in navigation
3. See the pending request
4. Click "Respond" to join the conversation
5. Send messages and mark as resolved when done

## Future Enhancements

- WebSocket integration for real-time message updates
- Push notifications for new support requests
- Support team availability status
- Typing indicators for live agents
- File attachment support in support messages
- Conversation ratings/feedback
- Support ticket export functionality
- Analytics dashboard for support metrics

---

**Generated**: 2025-10-14
**Version**: 1.0
