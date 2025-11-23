@props(['conversationId' => null])

<div x-data="aiChat({{ json_encode($conversationId) }})" class="ai-chat border border-gray-700 rounded-lg p-4 bg-gray-800">
    <div class="messages mb-3 space-y-2" style="max-height:400px;overflow-y:auto;">
        <template x-for="(m, index) in messages" :key="index">
            <div class="mb-2 p-2 rounded" :class="m.role === 'user' ? 'bg-blue-900' : 'bg-gray-900'">
                <strong class="text-indigo-300" x-text="m.role === 'user' ? 'You' : 'AI Assistant'"></strong>
                <div x-text="m.content" class="ml-2 text-gray-200 mt-1"></div>
            </div>
        </template>
        <div x-show="messages.length === 0" class="text-gray-500 text-center py-4">
            Start a conversation with the AI assistant...
        </div>
    </div>

    <div>
        <textarea 
            x-model="input" 
            @keydown.enter.ctrl="send()"
            class="w-full border border-gray-600 bg-gray-900 text-white p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" 
            rows="3" 
            placeholder="Ask the assistant... (Ctrl+Enter to send)"
            :disabled="sending"
        ></textarea>
        <div class="text-right mt-2">
            <button 
                @click.prevent="send()" 
                :disabled="sending || !input.trim()"
                class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-semibold px-6 py-2 rounded-lg transition"
                x-text="sending ? 'Sending...' : 'Send'"
            ></button>
        </div>
    </div>

    <script>
        function aiChat(initialConversationId) {
            return {
                messages: [],
                input: '',
                conversationId: initialConversationId || null,
                sending: false,

                async send() {
                    if (!this.input.trim() || this.sending) {
                        console.log('Send blocked: empty input or already sending');
                        return;
                    }
                    
                    const userMessage = this.input.trim();
                    this.messages.push({ role: 'user', content: userMessage });
                    const payload = { message: userMessage };
                    if (this.conversationId) payload.conversation_id = this.conversationId;
                    this.input = '';
                    this.sending = true;

                    console.log('Sending to /ai/chat:', payload);

                    try {
                        // Check if axios is available
                        if (typeof window.axios === 'undefined') {
                            throw new Error('Axios is not loaded. Please refresh the page.');
                        }

                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!token) {
                            throw new Error('CSRF token not found');
                        }

                        const res = await window.axios.post('/ai/chat', payload, { 
                            headers: { 'X-CSRF-TOKEN': token } 
                        });
                        
                        console.log('Response:', res.data);
                        
                        if (res.data.conversation_id) this.conversationId = res.data.conversation_id;
                        this.messages.push({ role: 'assistant', content: res.data.reply });
                        
                        // Scroll to bottom
                        setTimeout(() => {
                            const el = document.querySelector('.ai-chat .messages');
                            if (el) el.scrollTop = el.scrollHeight;
                        }, 50);
                    } catch (e) {
                        console.error('AI Chat Error:', e);
                        let errorMsg = 'Error: ';
                        if (e.response) {
                            console.error('Response data:', e.response.data);
                            errorMsg += e.response.data.error || e.response.data.message || 'Server error';
                        } else {
                            errorMsg += e.message;
                        }
                        this.messages.push({ role: 'assistant', content: errorMsg });
                    } finally {
                        this.sending = false;
                    }
                }
            }
        }
    </script>
</div>
