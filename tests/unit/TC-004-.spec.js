import {
    test,
    describe,
    expect,
    beforeEach
} from "vitest";
import {
    mount
} from "@vue/test-utils";
import StatComponent from "@/resources/js/Layout/Components/MainLayoutComponents/StatComponent.vue";

describe("StatComponent", () => {
    let wrapper

    beforeEach(async () => {
        wrapper = mount(StatComponent, { 
            props: {
                title: "Vue Test",
                value: 200,
                previousValue: 100,
                color: "#4469DE"
            }
        })

        await wrapper.vm.$nextTick()
    })

    test("renders the component", () => {
        expect(wrapper.exists()).toBeTruthy()
    })

    test("displays the title prop in #statTitle", () => {
        const titleElement = wrapper.find('#statTitle')
        expect(titleElement.exists()).toBeTruthy()
        expect(titleElement.text()).toBe("Vue Test")
    })

    test("displays the value prop in #currentValue", () => {
        const valueElement = wrapper.find('#currentValue')
        expect(valueElement.exists()).toBeTruthy()
        expect(valueElement.text()).toBe("200")
    })

    test("displays the previousValue prop in #lastMonth", () => {
        const previousValueElement = wrapper.find('#lastMonth')
        expect(previousValueElement.exists()).toBeTruthy()
        expect(previousValueElement.text()).toBe("Last month: 100")
    })

    test("calculates and displays the percentage correctly in #percentageSpan", () => {
        const percentageElement = wrapper.find('#percentageSpan')

        expect(percentageElement.exists()).toBeTruthy()
        expect(percentageElement.text()).toBe("+100.00%")
        expect(percentageElement.attributes('style')).toContain('color: rgb(68, 105, 222);') // #4469DE
    })

    test("handles zero previousValue for percentage calculation in #percentageSpan", async () => {
        await wrapper.setProps({ previousValue: 0, value: 50 })
        await wrapper.vm.$nextTick()

        const percentageElement = wrapper.find('#percentageSpan')
        expect(percentageElement.exists()).toBeTruthy()

        expect(percentageElement.text()).toBe("+?%") // !
    })

    test("handles NaN case for percentage calculation in #percentageSpan (e.g., previousValue is 0 and value is 0)", async () => {
        await wrapper.setProps({ previousValue: 0, value: 0 })
        await wrapper.vm.$nextTick()

        const percentageElement = wrapper.find('#percentageSpan')
        
        expect(percentageElement.exists()).toBeTruthy()
        expect(percentageElement.text()).toBe("+0%") // ! 
    })
})