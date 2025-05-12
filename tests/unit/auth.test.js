import {
    test,
    describe,
    expect
} from "vitest";
import {
    mount
} from "@vue/test-utils";
import Auth from "@/resources/js/Pages/Auth/Auth.vue";

describe("unit test test", () => {
    const wrapper = mount(Auth)

    test("unit test test", () => {
        expect(1 == 1).toBeTruthy()
    })
})