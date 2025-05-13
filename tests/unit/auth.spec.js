import {
    test,
    describe,
    expect,
    beforeEach
} from "vitest";
import {
    mount
} from "@vue/test-utils";
import Auth from "@/resources/js/Pages/Auth/Auth.vue";

describe("Auth unit test", () => {
    let wrapper

    beforeEach(async () => {
        wrapper = mount(Auth)
        await wrapper.vm.$nextTick()
    })

    test("Welcome view test", async () => {
        let welcomeMessage = wrapper.find("#welcomeMessage");
        const signInRadio = wrapper.find('#sign-in')
        const signUpRadio = wrapper.find('#sign-up')

        expect(wrapper.vm.viewState.isSignIn).toBeTruthy()
        expect(welcomeMessage.text()).toBe("Welcome Back")

        await signUpRadio.setChecked()
        await wrapper.vm.$nextTick()

        expect(wrapper.vm.viewState.isSignIn).toBeFalsy()

        welcomeMessage = wrapper.find("#welcomeMessage")
        expect(welcomeMessage.text()).toBe("Welcome")

        await signInRadio.setChecked()
        await wrapper.vm.$nextTick()

        expect(wrapper.vm.viewState.isSignIn).toBeTruthy()
        welcomeMessage = wrapper.find("#welcomeMessage")
        expect(welcomeMessage.text()).toBe("Welcome Back")
    })
})
