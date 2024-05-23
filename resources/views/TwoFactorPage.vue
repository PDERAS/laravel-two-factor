<template>
<LoginContainer v-if="authUser">
    <form @submit.prevent="verify">
        <div class="grid grid-cols-1 gap-4 my-5">
            <div class="text-sm">
                Please enter the verification code sent to {{ maskedEmail }}
            </div>

            <StyledMask
                v-model="code"
                class="verification-code"
                name="code"
                autocomplete="off"
                mask="######"
                placeholder="000000" />

            <div class="text-xs -mt-3">
                Code is valid for 5 minutes
            </div>
        </div>

        <div class="flex flex-col">
            <StyledButton
                :full="true"
                type="submit"
                class="mb-4">Submit</StyledButton>
            <LoginLink @click="logout">Back</LoginLink>
        </div>

        <div class="text-xs mt-3">
            <template v-if="resending">
                Resending...
            </template>
            <template v-else-if="countdown > 0">
                Code resent. Still haven't received it? You'll be able to request it again in {{ countdown }} seconds
            </template>
            <template v-else>
                Didn't receive the verification code?
                <LoginLink class="text-sm" @click="resendCode">Resend it</LoginLink>
            </template>
        </div>
    </form>
</LoginContainer>
</template>

<script>
import { mapState } from 'vuex';
import { axios } from '@pderas/axios-helper';
import * as LoginComponents from '@/components/Auth';

export default {
    components: {
        ...LoginComponents
    },

    data: _ => ({
        code: '',

        resending: false,
        resendLimit: 20,
        countdown: 0,
        countdownInterval: null,
    }),

    computed: {
        ...mapState('user', {
            authUser: 'profile'
        }),

        maskedEmail() {
            // Show the 1st and last character before the @ separated by asterisks
            const email = this.authUser.email;
            return email.slice(0, 1) + '***' + email.slice(email.indexOf('@') - 1, email.length);
        }
    },

    methods: {
        startCountdown() {
            this.countdown = this.resendLimit;
            this.countdownInterval = setInterval(() => (this.countdown -= 1), 1000);
        },

        stopCountdown() {
            this.resending = false;
            clearInterval(this.countdownInterval);
        },

        async resendCode() {
            this.resending = true;
            await axios.post('/2fa/resend').then(_ => {
                this.startCountdown();
            }).finally(_ => {
                this.resending = false;
            });
        },

        verify() {
            const payload = {
                code: this.code,
            };
            axios.post('/2fa', payload).then(_ => {
                this.$inertia.visit('/');
            });
        },

        logout() {
            this.$inertia.post('/logout');
        },
    },

    watch: {
        countdown(val) {
            if (val <= 0) {
                this.stopCountdown();
            }
        }
    },
};
</script>

<style scoped>
.verification-code:deep(input) {
    font-size: 2.25rem;
    line-height: 2.5rem;
    text-align: center;
    letter-spacing: 0.5rem;
}
</style>
